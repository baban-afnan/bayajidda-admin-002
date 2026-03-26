<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CableSubscription;
use Illuminate\Http\Request;

class CableSubscriptionController extends Controller
{
    /**
     * Display a listing of the cable subscriptions.
     */
    public function index(Request $request)
    {
        $query = CableSubscription::query();

        if ($request->filled('cablename')) {
            $query->where('cablename', 'like', '%' . $request->cablename . '%');
        }
        if ($request->filled('smart_card_number')) {
            $query->where('smart_card_number', 'like', '%' . $request->smart_card_number . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest()->paginate(20)->withQueryString();

        // Get unique values for filters
        $cablenames = CableSubscription::distinct()->pluck('cablename');
        $statuses = ['enabled' => 'Active', 'disabled' => 'Inactive'];
        
        return view('admin.cable-subscription.index', compact('subscriptions', 'cablenames', 'statuses'));
    }

    /**
     * Store a newly created cable subscription.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_ref' => 'required|string|unique:cable_subscriptions',
            'cablename' => 'required|string',
            'cableplan' => 'required|string',
            'smart_card_number' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 'enabled' : 'disabled';

        CableSubscription::create($validated);

        return back()->with('success', 'Cable Subscription created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CableSubscription $cableSubscription)
    {
        $validated = $request->validate([
            'transaction_ref' => 'required|string|unique:cable_subscriptions,transaction_ref,' . $cableSubscription->id,
            'cablename' => 'required|string',
            'cableplan' => 'required|string',
            'smart_card_number' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 'enabled' : 'disabled';

        $cableSubscription->update($validated);

        return back()->with('success', 'Cable Subscription updated successfully.');
    }

    /**
     * Update the status of the subscription.
     */
    public function updateStatus(Request $request, CableSubscription $cableSubscription)
    {
        $validated = $request->validate([
            'status' => 'required|in:enabled,disabled',
        ]);

        $cableSubscription->update(['status' => $validated['status']]);

        return back()->with('success', 'Subscription status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CableSubscription $cableSubscription)
    {
        $cableSubscription->delete();
        return back()->with('success', 'Cable Subscription deleted successfully.');
    }
}
