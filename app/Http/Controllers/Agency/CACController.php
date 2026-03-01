<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentService;
use App\Models\User;
use App\Models\ServiceField;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CACController extends Controller
{
    /**
     * List CAC Registration requests with filters and pagination
     */
    public function index(Request $request)
    {
        $search       = $request->input('search');
        $statusFilter = $request->input('status');

        $query = AgentService::query()
            ->select('agent_services.*', 'users.email as user_email')
            ->join('users', 'agent_services.user_id', '=', 'users.id')
            ->where('agent_services.service_type', 'CAC');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agent_services.reference', 'like', "%$search%")
                  ->orWhere('agent_services.performed_by', 'like', "%$search%")
                  ->orWhere('agent_services.user_id', 'like', "%$search%");
            });
        }

        if ($statusFilter) {
            $query->where('agent_services.status', $statusFilter);
        }

        $enrollments = $query
            ->orderByRaw("CASE agent_services.status
                WHEN 'pending'     THEN 1
                WHEN 'processing'  THEN 2
                WHEN 'in-progress' THEN 3
                WHEN 'query'       THEN 4
                WHEN 'resolved'    THEN 5
                WHEN 'successful'  THEN 6
                WHEN 'rejected'    THEN 7
                WHEN 'failed'      THEN 8
                WHEN 'remark'      THEN 9
                ELSE 999 END")
            ->orderByDesc('agent_services.submission_date')
            ->paginate(10);

        $statusCounts = [
            'pending'    => AgentService::where('service_type', 'CAC')->where('status', 'pending')->count(),
            'processing' => AgentService::where('service_type', 'CAC')->where('status', 'processing')->count(),
            'resolved'   => AgentService::where('service_type', 'CAC')->whereIn('status', ['resolved', 'successful'])->count(),
            'rejected'   => AgentService::where('service_type', 'CAC')->whereIn('status', ['rejected', 'failed'])->count(),
        ];

        return view('cac.index', compact('enrollments', 'search', 'statusFilter', 'statusCounts'));
    }

    /**
     * Show details of a single CAC Registration request
     */
    public function show($id)
    {
        $enrollmentInfo = AgentService::findOrFail($id);
        $user           = User::find($enrollmentInfo->user_id);

        // Standard pattern for status history in this project
        $statusHistory = collect([
            [
                'status'          => $enrollmentInfo->status,
                'comment'         => $enrollmentInfo->comment,
                'submission_date' => $enrollmentInfo->created_at,
                'updated_at'      => $enrollmentInfo->updated_at,
                'file_url'        => $enrollmentInfo->file_url,
            ]
        ]);

        return view('cac.show', compact('enrollmentInfo', 'statusHistory', 'user'));
    }

    /**
     * Update the status of a CAC Registration request
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'       => 'required|in:pending,processing,in-progress,resolved,successful,rejected,failed,query,remark',
            'comment'      => 'nullable|string',
            'file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'force_refund' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $enrollment = AgentService::findOrFail($id);
            $oldStatus  = $enrollment->status;

            // Handle file upload
            $fileUrl = $enrollment->file_url;
            if ($request->hasFile('file')) {
                if ($fileUrl && Storage::disk('public')->exists($fileUrl)) {
                    Storage::disk('public')->delete($fileUrl);
                }
                $file     = $request->file('file');
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('cac-files', $fileName, 'public');
                $baseUrl = rtrim(config('app.url'), '/');
                $fileUrl = $baseUrl . '/storage/cac-files/' . $fileName;
            }

            $enrollment->status  = $request->status;
            $enrollment->comment = $request->comment;
            $enrollment->file_url = $fileUrl;
            $enrollment->save();

            if ($request->status === 'rejected') {
                if ($oldStatus !== 'rejected' || $request->force_refund) {
                    $this->processRefund($enrollment, $request->force_refund);
                }
            }

            DB::commit();
            return redirect()->route('cac.index')
                ->with('successMessage', 'Status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cac.index')
                ->with('errorMessage', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Handle refund when a request is rejected
     */
    private function processRefund($enrollment, $forceRefund = false)
    {
        $serviceFieldId = $enrollment->service_field_id;
        $user           = User::find($enrollment->user_id);

        if (!$user)           throw new \Exception('User not found.');
        if (!$serviceFieldId) throw new \Exception('Service field ID is missing.');

        $serviceField = ServiceField::find($serviceFieldId);
        if (!$serviceField)   throw new \Exception('Service field not found.');

        $role          = strtolower($user->role ?? 'default');
        $refundExists  = Transaction::where('type', 'refund')
            ->where('description', 'LIKE', "%Request ID #{$enrollment->id}%")
            ->exists();

        if ($refundExists && !$forceRefund) {
            throw new \Exception('Refund already processed for this request.');
        }

        $servicePrice = DB::table('service_prices')
            ->where('service_fields_id', $serviceFieldId)
            ->where('user_type', $role)
            ->value('price');

        $basePrice = $servicePrice ?: $serviceField->base_price;
        if (!$basePrice || $basePrice <= 0) throw new \Exception('No valid price found for refund.');

        $refundAmount = round($basePrice * 0.8, 2);
        $debitAmount  = round($basePrice * 0.2, 2);

        $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();
        if (!$wallet) throw new \Exception('Wallet not found for user.');

        $wallet->balance += $refundAmount;
        $wallet->save();

        Transaction::create([
            'transaction_ref' => strtoupper(Str::random(12)),
            'user_id'         => $user->id,
            'performed_by'    => Auth::user()->first_name . ' ' . (Auth::user()->last_name ?? ''),
            'amount'          => $refundAmount,
            'fee'             => 0.00,
            'net_amount'      => $refundAmount,
            'description'     => "Refund 80% for rejected service [{$serviceField->field_name}], Request ID #{$enrollment->id}",
            'type'            => 'refund',
            'status'          => 'completed',
            'metadata'        => json_encode([
                'service_id'               => $enrollment->service_id,
                'service_field_id'         => $serviceFieldId,
                'field_code'               => $serviceField->field_code,
                'field_name'               => $serviceField->field_name ?? null,
                'user_role'                => $role,
                'base_price'               => $basePrice,
                'percentage_refunded'      => 80,
                'amount_debited_by_system' => $debitAmount,
                'forced_refund'            => $forceRefund,
            ]),
        ]);
    }
}
