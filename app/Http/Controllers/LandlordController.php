<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\Payment;

class LandlordController extends Controller
{
    /**
     * Display the landlord dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function landlordDashboard()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get landlord statistics
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
        
        // Get recent payments
        $recentPayments = Payment::whereHas('lease.unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->with('lease.unit.property')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get recent leases
        $recentLeases = Lease::whereHas('unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->with('unit.property')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get properties for quick access
        $properties = Property::where('owner_id', $user->id)
            ->withCount('units')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('landlord.dashboard', compact('stats', 'recentPayments', 'recentLeases', 'properties'));
    }
}
