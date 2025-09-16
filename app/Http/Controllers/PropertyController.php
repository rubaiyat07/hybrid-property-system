<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Unit;

class PropertyController extends Controller
{
    /**
     * Display a listing of the landlord's properties.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get properties owned by the authenticated landlord
        $properties = Property::where('owner_id', $user->id)
            ->withCount('units')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Count occupied units across all approved properties
        $occupiedUnits = Unit::whereHas('property', function($query) use ($user) {
            $query->where('owner_id', $user->id)
                  ->where('registration_status', Property::REGISTRATION_APPROVED);
        })->where('status', 'occupied')->count();
        
        // Statistics for different registration statuses
        $stats = [
            'total' => $properties->total(),
            'approved' => Property::where('owner_id', $user->id)->approved()->count(),
            'pending' => Property::where('owner_id', $user->id)->pending()->count(),
            'rejected' => Property::where('owner_id', $user->id)->rejected()->count(),
        ];
        
        return view('landlord.property.index', compact('properties', 'occupiedUnits', 'stats'));
    }

    /**
     * Show the form for creating a new property (registration form).
     */
    public function create()
    {
        $propertyTypes = [
            'apartment' => 'Apartment',
            'house' => 'House',
            'condo' => 'Condo',
            'townhouse' => 'Townhouse',
            'commercial' => 'Commercial',
            'other' => 'Other',
        ];
        
        return view('landlord.property.create', compact('propertyTypes'));
    }
    /**
     * Store a newly created property (registration request) in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'description' => 'nullable|string',
            'price_or_rent' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $property = new Property();
        $property->name = $request->name;
        $property->type = $request->type;
        $property->address = $request->address;
        $property->city = $request->city;
        $property->state = $request->state;
        $property->zip_code = $request->zip_code;
        $property->description = $request->description;
        $property->price_or_rent = $request->price_or_rent ?? 0;
        $property->owner_id = auth()->id();
        $property->status = Property::STATUS_INACTIVE; // Inactive until approved
        $property->registration_status = Property::REGISTRATION_PENDING; // Pending approval

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('properties', 'public');
            $property->image = '/storage/' . $imagePath;
        }

        $property->save();

        return redirect()->route('landlord.property.index')
            ->with('success', 'Property registration submitted successfully. It will be reviewed by admin.');
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        // Ensure the property belongs to the authenticated landlord
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $property->load(['units', 'leases.tenant', 'approver']);
        
        return view('landlord.property.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        // Ensure the property belongs to the authenticated landlord
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Don't allow editing if rejected - they need to create new registration
        if ($property->registration_status === Property::REGISTRATION_REJECTED) {
            return redirect()->route('landlord.property.show', $property)
                ->with('error', 'Cannot edit rejected properties. Please submit a new registration.');
        }

        $propertyTypes = [
            'apartment' => 'Apartment',
            'house' => 'House',
            'condo' => 'Condo',
            'townhouse' => 'Townhouse',
            'commercial' => 'Commercial',
            'other' => 'Other',
        ];
        
        return view('landlord.property.edit', compact('property', 'propertyTypes'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, Property $property)
    {
        // Ensure the property belongs to the authenticated landlord
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Don't allow editing if rejected
        if ($property->registration_status === Property::REGISTRATION_REJECTED) {
            return redirect()->route('landlord.property.show', $property)
                ->with('error', 'Cannot edit rejected properties.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'description' => 'nullable|string',
            'price_or_rent' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $property->name = $request->name;
        $property->type = $request->type;
        $property->address = $request->address;
        $property->city = $request->city;
        $property->state = $request->state;
        $property->zip_code = $request->zip_code;
        $property->description = $request->description;
        $property->price_or_rent = $request->price_or_rent ?? $property->price_or_rent;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('properties', 'public');
            $property->image = '/storage/' . $imagePath;
        }

        // If property was approved and is being edited, set back to pending for re-approval
        if ($property->registration_status === Property::REGISTRATION_APPROVED) {
            $property->registration_status = Property::REGISTRATION_PENDING;
            $property->approved_by = null;
            $property->approved_at = null;
            $property->registration_notes = null;
        }

        $property->save();

        return redirect()->route('landlord.property.index')
            ->with('success', 'Property updated successfully.' . 
                ($property->registration_status === Property::REGISTRATION_PENDING ? ' It will be reviewed again by admin.' : ''));
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property)
    {
        // Ensure the property belongs to the authenticated landlord
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if property has active leases
        $activeLeases = $property->leases()->where('end_date', '>=', now())->count();
        
        if ($activeLeases > 0) {
            return redirect()->route('landlord.property.index')
                ->with('error', 'Cannot delete property with active leases.');
        }

        $property->delete();

        return redirect()->route('landlord.property.index')
            ->with('success', 'Property deleted successfully.');
    }

    /**
     * Resubmit a rejected property for approval (create new registration)
     */
    public function resubmit(Property $property)
    {
        // Ensure the property belongs to the authenticated landlord
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow resubmission of rejected properties
        if ($property->registration_status !== Property::REGISTRATION_REJECTED) {
            return redirect()->route('landlord.property.show', $property)
                ->with('error', 'This action is only available for rejected properties.');
        }

        // Reset registration status
        $property->update([
            'registration_status' => Property::REGISTRATION_PENDING,
            'approved_by' => null,
            'approved_at' => null,
            'registration_notes' => null,
        ]);

        return redirect()->route('landlord.property.show', $property)
            ->with('success', 'Property resubmitted for review successfully.');
    }

    // Additional methods for agents (if needed)
    public function agentIndex()
    {
        // Logic for agents to view assigned approved properties
        $properties = Property::where('agent_id', auth()->id())
            ->approved() // Only show approved properties
            ->withCount('units')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('agent.properties.index', compact('properties'));
    }

    public function agentShow(Property $property)
    {
        // Ensure the property is assigned to the authenticated agent and approved
        if ($property->agent_id !== auth()->id() || !$property->is_approved) {
            abort(403, 'Unauthorized action.');
        }

        $property->load(['units', 'leases.tenant']);
        
        return view('agent.properties.show', compact('property'));
    }

    // Additional methods for buyers (if needed)
    public function buyerIndex(Request $request)
    {
        $query = Property::approved() // Only show approved properties
            ->where('status', Property::STATUS_ACTIVE);

        // Add search filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $properties = $query->withCount('units')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        return view('buyer.properties.index', compact('properties'));
    }

    public function buyerShow(Property $property)
    {
        // Only show approved and active properties to buyers
        if (!$property->is_approved || $property->status !== Property::STATUS_ACTIVE) {
            abort(404, 'Property not available.');
        }

        $property->load(['units', 'owner']);
        
        return view('buyer.properties.show', compact('property'));
    }
}