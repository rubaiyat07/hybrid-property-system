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

class TenantController extends Controller
{
    /**
     * Display a listing of the landlord's tenants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
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