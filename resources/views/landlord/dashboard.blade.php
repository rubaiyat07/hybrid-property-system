@extends('layouts.landlord')

@section('title', 'Landlord Dashboard')

@section('content')
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded p-4 text-center">
            <h3 class="font-semibold text-gray-700">Total Properties</h3>
            <p class="text-2xl font-bold">{{ $stats['total_properties'] }}</p>
        </div>
        <div class="bg-white shadow rounded p-4 text-center">
            <h3 class="font-semibold text-gray-700">Total Units</h3>
            <p class="text-2xl font-bold">{{ $stats['total_units'] }}</p>
        </div>
        <div class="bg-white shadow rounded p-4 text-center">
            <h3 class="font-semibold text-gray-700">Occupied Units</h3>
            <p class="text-2xl font-bold">{{ $stats['occupied_units'] }}</p>
        </div>
        <div class="bg-white shadow rounded p-4 text-center">
            <h3 class="font-semibold text-gray-700">Vacant Units</h3>
            <p class="text-2xl font-bold">{{ $stats['vacant_units'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <!-- Recent Leases -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Recent Leases</h3>
            <ul>
                @forelse($recentLeases as $lease)
                    <li class="border-b py-2">
                        {{ $lease->unit->property->name ?? 'Property' }} - {{ $lease->tenant->name ?? 'Tenant' }}  
                        <span class="text-gray-500 text-sm">({{ $lease->start_date }} - {{ $lease->end_date }})</span>
                    </li>
                @empty
                    <li>No recent leases found.</li>
                @endforelse
            </ul>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Recent Payments</h3>
            <ul>
                @forelse($recentPayments as $payment)
                    <li class="border-b py-2">
                        {{ $payment->lease->unit->property->name ?? 'Property' }} - ${{ $payment->amount }}  
                        <span class="text-gray-500 text-sm">({{ $payment->status }})</span>
                    </li>
                @empty
                    <li>No recent payments found.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
