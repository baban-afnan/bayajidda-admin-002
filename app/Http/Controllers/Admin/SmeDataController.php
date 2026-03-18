<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmeDataController extends Controller
{
    /**
     * Display a listing of the SME data plans.
     */
    public function index(Request $request)
    {
        $query = SmeData::query();

        if ($request->filled('network')) {
            $query->where('network', $request->network);
        }
        if ($request->filled('data_id')) {
            $query->where('data_id', 'like', '%' . $request->data_id . '%');
        }
        if ($request->filled('size')) {
            $query->where('size', 'like', '%' . $request->size . '%');
        }
        if ($request->filled('validity')) {
            $query->where('validity', 'like', '%' . $request->validity . '%');
        }

        $variations = $query->latest()->paginate(20)->withQueryString();

        // Get unique values for filters
        $networks = SmeData::distinct()->pluck('network');
        
        return view('admin.data-variations.sme-data', compact('variations', 'networks'));
    }

    /**
     * Store a newly created SME data plan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_id' => 'required|string',
            'network' => 'required|string',
            'plan_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'size' => 'required|string',
            'validity' => 'required|string',
            'status' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';

        SmeData::create($validated);

        return back()->with('success', 'SME Data Plan created successfully.');
    }

    public function sync()
    {
        try {
            $services = [
                'mtn_sme' => ['network' => 'MTN', 'plan_type' => 'SME'],
                'mtn_cg_lite' => ['network' => 'MTN', 'plan_type' => 'CG LITE'],
                'mtn_gifting' => ['network' => 'MTN', 'plan_type' => 'GIFTING'],
                'mtncg' => ['network' => 'MTN', 'plan_type' => 'CG'],
                'airtel_cg' => ['network' => 'AIRTEL', 'plan_type' => 'CG'],
                'airtel_sme' => ['network' => 'AIRTEL', 'plan_type' => 'SME'],
                'glo_data' => ['network' => 'GLO', 'plan_type' => 'DATA'],
                'glo_sme' => ['network' => 'GLO', 'plan_type' => 'SME'],
                'etisalat_data' => ['network' => '9MOBILE', 'plan_type' => 'DATA']
            ];

            $syncedCount = 0;
            $firstSuccess = true;

            foreach ($services as $serviceKey => $details) {
                $response = Http::get('https://api.gsubz.com/api/plans', [
                    'service' => $serviceKey
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['plans']) && is_array($data['plans'])) {
                        if ($firstSuccess) {
                            SmeData::truncate(); // Drop all existing plans
                            $firstSuccess = false;
                        }

                        foreach ($data['plans'] as $plan) {
                            $displayName = $plan['displayName'] ?? '';
                            $parts = explode('-', $displayName);
                            $size = trim($parts[0] ?? $displayName);
                            $validity = trim($parts[1] ?? '30 Days');

                            SmeData::create([
                                'data_id' => $plan['value'],
                                'network' => $details['network'],
                                'plan_type' => $details['plan_type'],
                                'amount' => $plan['price'],
                                'size' => $size,
                                'validity' => $validity,
                                'status' => 'active',
                            ]);
                            $syncedCount++;
                        }
                    }
                }
            }

            if ($syncedCount > 0) {
                return back()->with('success', "SME Data Plans synced successfully. Total: {$syncedCount} plans.");
            }
            
            return back()->with('error', 'Failed to fetch data from API.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmeData $smeData)
    {
        $validated = $request->validate([
            'data_id' => 'required|string',
            'network' => 'required|string',
            'plan_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'size' => 'required|string',
            'validity' => 'required|string',
            'status' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';

        $smeData->update($validated);

        return back()->with('success', 'SME Data Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmeData $smeData)
    {
        $smeData->delete();
        return back()->with('success', 'SME Data Plan deleted successfully.');
    }
}
