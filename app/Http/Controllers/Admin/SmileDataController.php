<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmilePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmileDataController extends Controller
{
    /**
     * Display a listing of the Smile data plans.
     */
    public function index(Request $request)
    {
        $query = SmilePlan::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('plan_id')) {
            $query->where('plan_id', 'like', '%' . $request->plan_id . '%');
        }

        $variations = $query->latest()->paginate(20)->withQueryString();

        // Get unique values for filters
        $networks = SmilePlan::distinct()->pluck('name');
        
        return view('admin.data-variations.smile-data', compact('variations', 'networks'));
    }

    /**
     * Store a newly created Smile data plan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        SmilePlan::create($validated);

        return back()->with('success', 'Smile Data Plan created successfully.');
    }

    public function sync()
    {
        try {
            $services = [
                'smile_data' => ['name' => 'SMILE']
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
                            SmilePlan::truncate(); // Drop all existing plans
                            $firstSuccess = false;
                        }

                        foreach ($data['plans'] as $plan) {
                            SmilePlan::create([
                                'plan_id' => $plan['value'],
                                'name' => $plan['displayName'] ?? $details['name'],
                                'price' => $plan['price'],
                            ]);
                            $syncedCount++;
                        }
                    }
                }
            }

            if ($syncedCount > 0) {
                return back()->with('success', "Smile Data Plans synced successfully. Total: {$syncedCount} plans.");
            }
            
            return back()->with('error', 'Failed to fetch data from API.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmilePlan $smilePlan)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $smilePlan->update($validated);

        return back()->with('success', 'Smile Data Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmilePlan $smilePlan)
    {
        $smilePlan->delete();
        return back()->with('success', 'Smile Data Plan deleted successfully.');
    }
}
