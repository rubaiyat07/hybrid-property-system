@extends('layouts.landlord')

@section('title', 'My Leases')

@section('content')
<h1 class="text-2xl font-bold mb-4">My Leases</h1>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Tenant</th>
            <th class="border px-4 py-2">Property</th>
            <th class="border px-4 py-2">Unit</th>
            <th class="border px-4 py-2">Start Date</th>
            <th class="border px-4 py-2">End Date</th>
            <th class="border px-4 py-2">Rent Amount</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($leases as $lease)
        <tr>
            <td class="border px-4 py-2">{{ $lease->tenant->user->name ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $lease->unit->property->address ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $lease->unit->unit_number ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $lease->start_date->format('M d, Y') }}</td>
            <td class="border px-4 py-2">{{ $lease->end_date->format('M d, Y') }}</td>
            <td class="border px-4 py-2">${{ number_format($lease->rent_amount, 2) }}</td>
            <td class="border px-4 py-2">{{ ucfirst($lease->status) }}</td>
            <td class="border px-4 py-2">
                <a href="{{ route('landlord.leases.show', $lease->id) }}" class="text-blue-600 hover:underline">View</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="border px-4 py-2 text-center text-gray-500">No leases found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $leases->links() }}
</div>

<div class="mt-4">
    <a href="{{ route('landlord.leases.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create New Lease</a>
</div>
@endsection
