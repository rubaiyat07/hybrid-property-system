{{-- File: resources/views/landlord/property/index.blade.php --}}
@extends('layouts.landlord')

@section('title', 'My Properties')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">My Properties</h1>
</div>

<!-- Properties Statistics -->
<div class="property-stats-grid">
    <div class="stat-card">
        <h3 class="stat-label">Total Properties</h3>
        <p class="stat-value">{{ $stats['total'] }}</p>
    </div>
    <div class="stat-card">
        <h3 class="stat-label">Approved</h3>
        <p class="stat-value">{{ $stats['approved'] }}</p>
    </div>
    <div class="stat-card">
        <h3 class="stat-label">Pending Review</h3>
        <p class="stat-value">{{ $stats['pending'] }}</p>
    </div>
    <div class="stat-card">
        <h3 class="stat-label">Occupied Units</h3>
        <p class="stat-value">{{ $occupiedUnits ?? 0 }}</p>
    </div>
</div>

<!-- Information Alert -->
<div class="alert alert-info mb-6">
    <div class="alert-icon">
        <i class="fas fa-info-circle"></i>
    </div>
    <div class="alert-content">
        <h3>Property Registration Process</h3>
        <p>All properties must be approved by an administrator before you can add units for rental. The approval process typically takes 1-2 business days.</p>
    </div>
</div>

<!-- Properties List -->
<div class="property-table-container">
    <div class="property-table-header">
        <h2>Properties List</h2>
    </div>
    
    @if($properties->count() > 0)
        <div class="overflow-x-auto">
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Registration Status</th>
                        <th>Units</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="property-image-container">
                                        @if($property->image)
                                            <img class="property-image" 
                                                 src="{{ $property->image }}" alt="{{ $property->name }}">
                                        @else
                                            <div class="property-image-placeholder">
                                                <i class="fas fa-building"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $property->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: #{{ $property->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-gray-900">{{ $property->address ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $property->city ?? '' }}, {{ $property->state ?? '' }}</div>
                            </td>
                            <td>
                                <div class="text-sm text-gray-900">{{ ucfirst($property->type ?? 'N/A') }}</div>
                            </td>
                            <td>
                                @if($property->registration_status === 'pending')
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-clock"></i>
                                        Pending Review
                                    </span>
                                @elseif($property->registration_status === 'approved')
                                    <span class="status-badge status-approved">
                                        <i class="fas fa-check-circle"></i>
                                        Approved
                                    </span>
                                @elseif($property->registration_status === 'rejected')
                                    <span class="status-badge status-rejected">
                                        <i class="fas fa-times-circle"></i>
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($property->registration_status === 'approved')
                                    <div class="text-sm text-gray-900">{{ $property->units_count ?? 0 }} units</div>
                                @else
                                    <div class="text-sm text-gray-400">
                                        <i class="fas fa-lock mr-1"></i>
                                        Approval required
                                    </div>
                                @endif
                            </td>
                            <td class="text-sm text-gray-500">
                                {{ $property->created_at ? $property->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('landlord.property.show', $property) }}" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($property->registration_status !== 'rejected')
                                        <a href="{{ route('landlord.property.edit', $property) }}" 
                                           class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @if($property->registration_status === 'approved')
                                        <a href="{{ route('landlord.units.create', ['property_id' => $property->id]) }}" 
                                           class="btn-action btn-add" title="Add Unit">
                                            <i class="fas fa-plus-square"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(method_exists($properties, 'links'))
            <div class="pagination-container">
                {{ $properties->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-building"></i>
            </div>
            <h3 class="empty-state-title">No properties found</h3>
            <p class="empty-state-description">Get started by registering your first property.</p>
            <div class="mt-6">
                <a href="{{ route('landlord.property.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i>
                    Register Property
                </a>
            </div>
        </div>
    @endif
</div>

@if($stats['rejected'] > 0)
    <!-- Rejected Properties Notice -->
    <div class="alert alert-error mt-6">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <h3>Rejected Properties</h3>
            <p>You have {{ $stats['rejected'] }} rejected {{ Str::plural('property', $stats['rejected']) }}. You can view the rejection reasons and resubmit them after making necessary changes.</p>
        </div>
    </div>
@endif

@endsection