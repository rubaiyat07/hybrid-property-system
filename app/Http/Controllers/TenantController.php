<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Property;


class TenantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display tenant homepage.
     */
    public function homepage()
    {
        $user = Auth::user();
        $tenant = $user->tenant()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => explode(' ', $user->name)[0] ?? $user->name,
                'last_name' => trim(str_replace(explode(' ', $user->name)[0] ?? '', $user->name, '')),
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        );

        $profile = $tenant;

        // Stats
        $stats = [
            'active_leases' => Lease::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->count(),
            'total_leases' => Lease::where('tenant_id', $tenant->id)->count(),
            'pending_payments' => Payment::where('tenant_id', $tenant->id)
                ->where('status', 'pending')
                ->count(),
            'total_paid' => Payment::where('tenant_id', $tenant->id)
                ->where('status', 'paid')
                ->sum('amount'),
        ];

        // Current lease
        $currentLease = Lease::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->with('unit.property')
            ->first();

        // Recent payments
        $recentPayments = Payment::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming payments
        $upcomingPayments = Payment::where('tenant_id', $tenant->id)
            ->where('due_date', '>', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Recent maintenance
        $recentMaintenance = MaintenanceRequest::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Sponsored ads (reuse from landlord or public)
        $ads = collect(); // Placeholder, no Ad model yet

        // Active listings for tenants (top 5 vacant published units)
        $activeListings = Unit::availableForListing()
            ->with('property')
            ->take(5)
            ->get();

        // Active rentals (top 5 active leases for this tenant)
        $activeRentals = Lease::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->with(['tenant.user', 'unit.property'])
            ->take(5)
            ->get();

        return view('tenant.homepage', compact(
            'profile', 'stats', 'currentLease', 'recentPayments',
            'upcomingPayments', 'recentMaintenance', 'ads',
            'activeListings', 'activeRentals'
        ));
    }

    /**
     * Display find rentals page for tenants.
     */
    public function rentalsIndex(Request $request)
    {
        $query = Unit::availableForListing()
            ->with('property.images') // Assuming Property has images
            ->orderBy('created_at', 'desc');

        // Basic filters (can be expanded)
        if ($request->filled('location')) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        if ($request->filled('max_price')) {
            $query->where('rent_amount', '<=', $request->max_price);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        $vacantUnits = $query->paginate(10);

        return view('tenant.rentals.index', compact('vacantUnits'));
    }

    // Additional methods can be added for inquiries, etc.
    public function createInquiry(Request $request, Unit $unit)
    {
        // Handle inquiry creation (create UnitInquiry)
        // Redirect back with success
    }

    // Landlord tenant management methods
    public function index(Request $request)
    {
        $query = Tenant::with('user', 'leases.unit.property');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Property filter
        if ($request->filled('property_id')) {
            $query->whereHas('leases.unit.property', function ($q) use ($request) {
                $q->where('id', $request->property_id);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->whereHas('leases', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $tenants = $query->paginate(10);

        // Get approved properties for filter dropdown
        $properties = Property::where('owner_id', auth()->id())
            ->approved()
            ->get();

        return view('landlord.tenants.index', compact('tenants', 'properties'));
    }

    public function create()
    {
        return view('landlord.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user first
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        // Assign tenant role
        $user->assignRole('Tenant');

        // Create tenant record
        $tenant = Tenant::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('user', 'leases.unit.property', 'payments');
        return view('landlord.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('landlord.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $tenant->user_id,
            'phone' => 'nullable|string|max:20',
        ]);

        // Update user
        $tenant->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update tenant
        $tenant->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->user->delete(); // This will cascade delete tenant due to foreign key
        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant deleted successfully.');
    }
}
