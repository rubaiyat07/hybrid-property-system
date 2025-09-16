{{-- File: resources/views/landlord/property/index.blade.php --}}
@extends('layouts.landlord')

@section('title', 'My Properties')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">My Properties</h1>
    <a href="{{ route('landlord.property.create') }}" 
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
        <i class="fas fa-plus mr-1"></i> Register Property
    </a>
</div>

<!-- Properties Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Total Properties</h3>
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Approved</h3>
        <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Pending Review</h3>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Occupied Units</h3>
        <p class="text-2xl font-bold text-purple-600">{{ $occupiedUnits ?? 0 }}</p>
    </div>
</div>

<!-- Information Alert -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Property Registration Process</h3>
            <div class="mt-2 text-sm text-blue-700">
                <p>All properties must be approved by an administrator before you can add units for rental. The approval process typically takes 1-2 business days.</p>
            </div>
        </div>
    </div>
</div>

<!-- Properties List -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Properties List</h2>
    </div>
    
    @if($properties->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Property
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Location
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registration Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Units
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($properties as $property)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        @if($property->image)
                                            <img class="h-12 w-12 rounded-lg object-cover" 
                                                 src="{{ $property->image }}" alt="{{ $property->name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-building text-gray-400"></i>
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
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $property->address ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $property->city ?? '' }}, {{ $property->state ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($property->type ?? 'N/A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($property->registration_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending Review
                                    </span>
                                @elseif($property->registration_status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Approved
                                    </span>
                                @elseif($property->registration_status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($property->registration_status === 'approved')
                                    <div class="text-sm text-gray-900">{{ $property->units_count ?? 0 }} units</div>
                                @else
                                    <div class="text-sm text-gray-400">
                                        <i class="fas fa-lock mr-1"></i>
                                        Approval required
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $property->created_at ? $property->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('landlord.property.show', $property) }}" 
                                       class="text-indigo-600 hover:text-indigo-900" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($property->registration_status !== 'rejected')
                                        <a href="{{ route('landlord.property.edit', $property) }}" 
                                           class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @if($property->registration_status === 'approved')
                                        <a href="{{ route('landlord.units.create', ['property_id' => $property->id]) }}" 
                                           class="text-green-600 hover:text-green-900" title="Add Unit">
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
            <div class="px-6 py-4">
                {{ $properties->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto h-24 w-24 text-gray-300">
                <i class="fas fa-building text-6xl"></i>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No properties found</h3>
            <p class="mt-2 text-gray-500">Get started by registering your first property.</p>
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
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Rejected Properties</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>You have {{ $stats['rejected'] }} rejected {{ Str::plural('property', $stats['rejected']) }}. You can view the rejection reasons and resubmit them after making necessary changes.</p>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection