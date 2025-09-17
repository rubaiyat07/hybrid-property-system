<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\MaintenanceRequest;

class TenantController extends Controller
{
    public function tenantHomepage()
    {
        $user = Auth::user();

        // Ensure user has Tenant role
        if (!$user->hasRole('Tenant')) {
            abort(403, 'Unauthorized');
        }

        $tenant = Tenant::where('user_id', $user->id)->first();

        if (!$tenant) {
            // Create tenant record if it doesn't exist
            $tenant = Tenant::create([
                'user_id' => $user->id,
                'emergency_contact' => null,
                'is_screened' => false,
                'move_in_date' => null,
                'move_out_date' => null,
            ]);
        }

        // Tenant statistics
        $stats = [
            'active_leases' => Lease::where('tenant_id', $tenant->id)->where('end_date', '>=', now())->count(),
            'total_leases' => Lease::where('tenant_id', $tenant->id)->count(),
            'pending_payments' => Payment::where('tenant_id', $tenant->id)->where('status', 'pending')->count(),
            'total_paid' => Payment::where('tenant_id', $tenant->id)->where('status', 'paid')->sum('amount'),
            'maintenance_requests' => MaintenanceRequest::where('tenant_id', $tenant->id)->count(),
            'open_maintenance' => MaintenanceRequest::where('tenant_id', $tenant->id)->where('status', 'open')->count(),
        ];

        // Current lease
        $currentLease = Lease::where('tenant_id', $tenant->id)
            ->where('end_date', '>=', now())
            ->with('unit.property')
            ->first();

        // Recent payments
        $recentPayments = Payment::where('tenant_id', $tenant->id)
            ->with('lease.unit.property')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming payments
        $upcomingPayments = Payment::where('tenant_id', $tenant->id)
            ->where('due_date', '>=', now())
            ->where('status', 'pending')
            ->with('lease.unit.property')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Recent maintenance requests
        $recentMaintenance = MaintenanceRequest::where('tenant_id', $tenant->id)
            ->with('unit.property')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Profile completion calculation
        $profile = $user;
        $profileCompletion = 0;
        if ($user->profile_photo) $profileCompletion += 20;
        if ($user->phone_verified) $profileCompletion += 20;
        if ($user->bio) $profileCompletion += 20;
        if ($user->documents_verified) $profileCompletion += 20;
        if ($user->screening_verified) $profileCompletion += 20;

        return view('tenant.homepage', compact(
            'stats',
            'currentLease',
            'recentPayments',
            'upcomingPayments',
            'recentMaintenance',
            'profile',
            'profileCompletion'
        ));
    }

    public function screeningStatus()
    {
        $user = Auth::user();

        if (!$user->hasRole('Tenant')) {
            abort(403, 'Unauthorized');
        }

        $tenant = $user->tenant;

        $screenings = $tenant ? $tenant->screenings()->get() : collect();

        return view('tenant.screening.status', compact('screenings'));
    }

    public function applications()
    {
        $user = Auth::user();

        if (!$user->hasRole('Tenant')) {
            abort(403, 'Unauthorized');
        }

        // For now, return empty applications - Application model not implemented yet
        $applications = collect();

        return view('tenant.applications.index', compact('applications'));
    }

    public function submitApplication(Request $request, $propertyId)
    {
        $user = Auth::user();

        if (!method_exists($user, 'hasRole') || !$user->hasRole('Tenant')) {
            abort(403, 'Unauthorized');
        }

        $tenant = $user->tenant;

        if (!$tenant || !$user->screening_verified) {
            return redirect()->back()->withErrors(['screening' => 'You must be a verified tenant to submit an application.']);
        }

        // Validate application data
        $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        // For now, just return success - Application model not implemented yet
        // TODO: Implement Application model and logic

        return redirect()->route('tenant.applications.index')->with('success', 'Application submitted successfully.');
    }

    public function storeScreeningDocuments(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('Tenant')) {
            abort(403, 'Unauthorized');
        }

        $tenant = $user->tenant;

        if (!$tenant) {
            return redirect()->back()->withErrors(['error' => 'Tenant profile not found.']);
        }

        // Validate the uploaded files
        $request->validate([
            'id_document' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // 5MB
            'income_proof' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'references' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $documents = [
            'id_document' => 'Government ID',
            'income_proof' => 'Proof of Income',
            'references' => 'Rental References',
        ];

        foreach ($documents as $field => $type) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('tenant_screenings', $fileName, 'public');

                // Create screening record
                \App\Models\TenantScreening::create([
                    'tenant_id' => $tenant->id,
                    'document_type' => $type,
                    'file_path' => $filePath,
                    'status' => 'pending',
                    'notes' => $request->input('notes'),
                ]);
            }
        }

        return redirect()->route('tenant.screening.status')->with('success', 'Screening documents submitted successfully. You will be notified once they are reviewed.');
    }

    public function screeningSubmitForm()
    {
        $user = Auth::user();

        if (!$user->hasRole('Tenant')) {
            abort(403, 'Unauthorized');
        }

        return view('tenant.screening.submit');
    }

    /**
     * Display a listing of tenants.
     * For Admin: shows all tenants
     * For Landlord: shows tenants of their properties
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            // Admin view: all tenants
            $tenants = Tenant::with([
                'user',
                'leases.unit.property',
                'payments' => function($query) {
                    $query->latest();
                }
            ])
            ->paginate(15);

            // Statistics for all tenants
            $stats = [
                'total_tenants' => Tenant::count(),
                'active_leases' => Lease::where('end_date', '>=', now())->count(),
                'pending_screening' => Tenant::where('is_screened', false)->count(),
                'overdue_payments' => Payment::where('status', 'pending')
                    ->where('due_date', '<', now())
                    ->count(),
            ];

            return view('admin.tenants.index', compact('tenants', 'stats'));
        } else {
            // Landlord view: tenants of their properties
            // Get all tenants associated with landlord's properties
            $tenants = Tenant::whereHas('leases.unit.property', function($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })
                ->with([
                    'user',
                    'leases.unit.property',
                    'payments' => function($query) {
                        $query->latest();
                    }
                ])
                ->paginate(15);

            // Statistics for the landlord's tenants
            $stats = [
                'total_tenants' => Tenant::whereHas('leases.unit.property', function($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })->count(),

                'active_leases' => Lease::whereHas('unit.property', function($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })->where('end_date', '>=', now())->count(),

                'pending_screening' => Tenant::whereHas('leases.unit.property', function($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })->where('is_screened', false)->count(),

                'overdue_payments' => Payment::whereHas('lease.unit.property', function($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->count(),
            ];

            // Get landlord's properties for filters
            $properties = Property::where('owner_id', $user->id)
                ->select('id', 'address')
                ->get();

            return view('landlord.tenants.index', compact('tenants', 'stats', 'properties'));
        }
    }

    /**
     * Show the form for creating a new tenant.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $properties = Property::where('owner_id', $user->id)->get();

        return view('landlord.tenants.create', compact('properties'));
    }

    /**
     * Store a newly created tenant in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:units,id',
            'move_in_date' => 'nullable|date',
        ]);

        // Check if user already has a tenant record
        $user = User::where('email', $request->email)->first();
        $tenant = Tenant::where('user_id', $user->id)->first();

        if (!$tenant) {
            $tenant = Tenant::create([
                'user_id' => $user->id,
                'move_in_date' => $request->move_in_date,
            ]);
        }

        // Assign tenant role if not already assigned
        if (!$user->hasRole('Tenant')) {
            $user->assignRole('Tenant');
        }

        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Tenant added successfully!');
    }

    /**
     * Display the specified tenant.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $tenant = Tenant::whereHas('leases.unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->with([
                'user',
                'leases.unit.property',
                'payments.lease',
                'maintenanceRequests',
                'screenings'
            ])
            ->findOrFail($id);

        return view('landlord.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified tenant.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();

        $tenant = Tenant::whereHas('leases.unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->with('user', 'leases.unit.property')
            ->findOrFail($id);

        $properties = Property::where('owner_id', $user->id)->get();

        return view('landlord.tenants.edit', compact('tenant', 'properties'));
    }

    /**
     * Update the specified tenant in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $tenant = Tenant::whereHas('leases.unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->findOrFail($id);

        $request->validate([
            'emergency_contact' => 'nullable|string|max:255',
            'move_in_date' => 'nullable|date',
            'move_out_date' => 'nullable|date|after:move_in_date',
        ]);

        $tenant->update($request->only([
            'emergency_contact',
            'move_in_date',
            'move_out_date'
        ]));

        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Tenant updated successfully!');
    }

    /**
     * Remove the specified tenant from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $tenant = Tenant::whereHas('leases.unit.property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->findOrFail($id);

        // Check if tenant has active leases
        $activeLeases = $tenant->leases()->where('end_date', '>=', now())->count();

        if ($activeLeases > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove tenant with active leases.'
            ], 400);
        }

        $tenant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tenant removed successfully!'
        ]);
    }

    /**
     * Get units for a specific property (AJAX)
     */
    public function getUnits($propertyId)
    {
        $user = Auth::user();

        $units = Unit::whereHas('property', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->where('property_id', $propertyId)
            ->where('status', 'vacant')
            ->select('id', 'unit_number', 'rent_amount')
            ->get();

        return response()->json($units);
    }


    /**
     * Search and filter tenants
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = Tenant::whereHas('leases.unit.property', function($q) use ($user) {
            $q->where('owner_id', $user->id);
        })->with(['user', 'leases.unit.property', 'payments']);

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by property
        if ($request->has('property_id') && $request->property_id) {
            $query->whereHas('leases.unit.property', function($q) use ($request) {
                $q->where('properties.id', $request->property_id);
            });
        }

        // Filter by lease status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->whereHas('leases', function($q) {
                    $q->where('end_date', '>=', now());
                });
            } elseif ($request->status === 'expired') {
                $query->whereHas('leases', function($q) {
                    $q->where('end_date', '<', now());
                });
            }
        }

        $tenants = $query->paginate(15);

        return response()->json([
            'html' => view('landlord.tenants.partials.tenant-table', compact('tenants'))->render(),
            'pagination' => $tenants->links()->render()
        ]);
    }


}
