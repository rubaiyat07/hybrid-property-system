@extends('layouts.tenant')

@section('title', 'Homepage')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Home</h1>
    @if($profile->screening_verified)
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
            Verified Tenant
        </span>
    @endif
</div>
<div class="grid grid-cols-4 gap-6 mb-6">
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Active Leases</h3>
        <p class="text-2xl font-bold">{{ $stats['active_leases'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Total Leases</h3>
        <p class="text-2xl font-bold">{{ $stats['total_leases'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Pending Payments</h3>
        <p class="text-2xl font-bold">{{ $stats['pending_payments'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Total Paid</h3>
        <p class="text-2xl font-bold">${{ number_format($stats['total_paid'], 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    <!-- Left (Main Content) -->
    <div class="col-span-2 space-y-6">

        <!-- 🔹 Current Lease -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Current Lease</h3>
            @if($currentLease)
                <div class="border rounded p-4">
                    <h4 class="font-medium">{{ $currentLease->unit->property->address }}</h4>
                    <p class="text-sm text-gray-600">Unit: {{ $currentLease->unit->unit_number }}</p>
                    <p class="text-sm text-gray-600">Rent: ${{ number_format($currentLease->rent_amount, 2) }}/month</p>
                    <p class="text-sm text-gray-600">Start: {{ $currentLease->start_date->format('M d, Y') }} - End: {{ $currentLease->end_date->format('M d, Y') }}</p>
                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">{{ ucfirst($currentLease->status) }}</span>
                </div>
            @else
                <p class="text-gray-500">No active lease found.</p>
            @endif
        </div>

        <!-- 🔹 Recent Payments -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Recent Payments</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Due Date</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $payment->lease->unit->property->address ?? 'N/A' }}</td>
                            <td class="px-4 py-2">${{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-2">{{ $payment->due_date->format('M d, Y') }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">{{ ucfirst($payment->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No recent payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🔹 Upcoming Payments -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Upcoming Payments</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Due Date</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingPayments as $payment)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $payment->lease->unit->property->address ?? 'N/A' }}</td>
                            <td class="px-4 py-2">${{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-2">{{ $payment->due_date->format('M d, Y') }}</td>
                            <td class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">{{ ucfirst($payment->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No upcoming payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🔹 Recent Maintenance Requests -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Recent Maintenance Requests</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Issue</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMaintenance as $request)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $request->unit->property->address ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($request->description, 50) }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded {{ $request->status == 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $request->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No recent maintenance requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Right (Sidebar: Profile Brief) -->
    <div class="col-span-1">
        @include('partials.profile-brief')
    </div>
</div>

@endsection
