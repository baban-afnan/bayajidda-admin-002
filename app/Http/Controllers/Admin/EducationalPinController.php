<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalPin;
use Illuminate\Http\Request;

class EducationalPinController extends Controller
{
    /**
     * Display a listing of the educational pins.
     */
    public function index(Request $request)
    {
        $query = EducationalPin::query();

        if ($request->filled('exam_name')) {
            $query->where('exam_name', 'like', '%' . $request->exam_name . '%');
        }
        if ($request->filled('transaction_ref')) {
            $query->where('transaction_ref', 'like', '%' . $request->transaction_ref . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pins = $query->latest()->paginate(20)->withQueryString();

        // Get unique values for filters
        $examNames = EducationalPin::distinct()->pluck('exam_name');
        $statuses = ['enabled' => 'Active', 'disabled' => 'Inactive'];
        
        return view('admin.educational-pin.index', compact('pins', 'examNames', 'statuses'));
    }

    /**
     * Store a newly created educational pin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_ref' => 'required|string|unique:educational_pins',
            'exam_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'pins' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 'enabled' : 'disabled';

        EducationalPin::create($validated);

        return back()->with('success', 'Educational Pin created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EducationalPin $educationalPin)
    {
        $validated = $request->validate([
            'transaction_ref' => 'required|string|unique:educational_pins,transaction_ref,' . $educationalPin->id,
            'exam_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'pins' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 'enabled' : 'disabled';

        $educationalPin->update($validated);

        return back()->with('success', 'Educational Pin updated successfully.');
    }

    /**
     * Update the status of the pin.
     */
    public function updateStatus(Request $request, EducationalPin $educationalPin)
    {
        $validated = $request->validate([
            'status' => 'required|in:enabled,disabled',
        ]);

        $educationalPin->update(['status' => $validated['status']]);

        return back()->with('success', 'Pin status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationalPin $educationalPin)
    {
        $educationalPin->delete();
        return back()->with('success', 'Educational Pin deleted successfully.');
    }
}
