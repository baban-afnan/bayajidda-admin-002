<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KiraniPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KiraniDataController extends Controller
{
    /**
     * Display a listing of the Kirani data plans.
     */
    public function index(Request $request)
    {
        $query = KiraniPlan::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('plan_id')) {
            $query->where('plan_id', 'like', '%' . $request->plan_id . '%');
        }

        $variations = $query->latest()->paginate(20)->withQueryString();

        // Get unique values for filters (optional, since name is now text input in blade usually)
        $networks = KiraniPlan::distinct()->pluck('name');
        
        return view('admin.data-variations.kirani-data', compact('variations', 'networks'));
    }

    /**
     * Store a newly created Kirani data plan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        KiraniPlan::create($validated);

        return back()->with('success', 'Kirani Data Plan created successfully.');
    }

    public function sync()
    {
        try {
            $services = [
                'mtn_kirani' => ['name' => 'MTN'],
                'airtel_kirani' => ['name' => 'AIRTEL'],
                'glo_kirani' => ['name' => 'GLO'],
                'etisalat_kirani' => ['name' => '9MOBILE']
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
                            KiraniPlan::truncate(); // Drop all existing plans
                            $firstSuccess = false;
                        }

                        foreach ($data['plans'] as $plan) {
                            KiraniPlan::create([
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
                return back()->with('success', "Kirani Data Plans synced successfully. Total: {$syncedCount} plans.");
            }
            
            return back()->with('error', 'Failed to fetch data from API.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KiraniPlan $kiraniPlan)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $kiraniPlan->update($validated);

        return back()->with('success', 'Kirani Data Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KiraniPlan $kiraniPlan)
    {
        $kiraniPlan->delete();
        return back()->with('success', 'Kirani Data Plan deleted successfully.');
    }
}
