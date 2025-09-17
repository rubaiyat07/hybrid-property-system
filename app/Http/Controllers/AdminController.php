<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;

class AdminController extends Controller
{
    // Admin dashboard
    public function adminDashboard()
    {
        // Example data to show on dashboard
        $totalUsers = User::count();
        $totalProperties = Property::count();

        return view('admin.dashboard', compact('totalUsers', 'totalProperties'));
    }

    // Tenant Screening Management
    public function screenings()
    {
        $screenings = \App\Models\TenantScreening::with(['tenant.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.screenings.index', compact('screenings'));
    }

    public function showScreening($screeningId)
    {
        $screening = \App\Models\TenantScreening::with(['tenant.user', 'reviewer'])
            ->findOrFail($screeningId);

        return view('admin.screenings.show', compact('screening'));
    }

    public function approveScreening(Request $request, $screeningId)
    {
        $screening = \App\Models\TenantScreening::findOrFail($screeningId);

        $screening->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        // Update user's screening_verified status
        $screening->tenant->user->update(['screening_verified' => true]);

        return redirect()->route('admin.screenings.index')->with('success', 'Screening approved successfully.');
    }

    public function rejectScreening(Request $request, $screeningId)
    {
        $screening = \App\Models\TenantScreening::findOrFail($screeningId);

        $screening->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('admin.screenings.index')->with('success', 'Screening rejected.');
    }
}
