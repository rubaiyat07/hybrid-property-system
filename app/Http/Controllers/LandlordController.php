<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\Payment;
// যদি পরবর্তীতে DB থেকে Ads আনতে চাও তাহলে নিচে যোগ করো
// use App\Models\Ad;

class LandlordController extends Controller
{
    public function landlordHomepage()
    {
        $user = Auth::user();
        
        // Landlord statistics
        $stats = [
            'total_properties' => Property::where('owner_id', $user->id)->count(),
            'total_units' => Unit::whereHas('property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->count(),
            'occupied_units' => Unit::whereHas('property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->where('status', 'occupied')->count(),
            'vacant_units' => Unit::whereHas('property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->where('status', 'vacant')->count(),
            'total_leases' => Lease::whereHas('unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->count(),
            'active_leases' => Lease::whereHas('unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->where('end_date', '>=', now())->count(),
            'total_income' => Payment::whereHas('lease.unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->sum('amount'),
            'pending_payments' => Payment::whereHas('lease.unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->where('status', 'pending')->count(),
        ];
        
        // Recent payments
        $recentPayments = Payment::whereHas('lease.unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->with('lease.unit.property')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Recent leases
        $recentLeases = Lease::whereHas('unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->with('unit.property')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Properties for quick access
        $properties = Property::where('owner_id', $user->id)
            ->withCount('units')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Dummy ads data 
        $ads = [
            (object) ['image_url' => '/images/ad1.jpg'],
            (object) ['image_url' => '/images/ad2.jpg'],
            (object) ['image_url' => '/images/ad3.jpg'],
        ];

        // Active Listings (Other landlords/agents)
    $activeListings = Property::where('owner_id', '!=', $user->id)
        ->with('user') // relation to owner/agent
        ->where('status', 'active')
        ->latest()
        ->take(10)
        ->get();

        // Active Rentals (Other landlords)
    $activeRentals = Lease::whereHas('unit.property', fn($q) => $q->where('owner_id', '!=', $user->id))
        ->with(['unit.property', 'tenant'])
        ->where('end_date', '>=', now())
        ->latest()
        ->take(10)
        ->get();

        // Profile completion calculation
        $profile = $user;
        $profileCompletion = 0;
        if ($user->profile_photo) $profileCompletion += 20;
        if ($user->phone_verified) $profileCompletion += 20;
        if ($user->bio) $profileCompletion += 20;
        if ($user->documents_verified) $profileCompletion += 20;
        if ($user->screening_verified) $profileCompletion += 20;

        return view('landlord.homepage', compact(
            'stats',
            'recentPayments',
            'recentLeases',
            'properties',
            'ads',
            'activeListings',
            'activeRentals',
            'profile',
            'profileCompletion'
        ));
    }
}
