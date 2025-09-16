@extends('layouts.landlord')

@section('title', 'Homepage')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Home</h1>
    
</div>
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

<div class="grid grid-cols-3 gap-6">
    <!-- Left (Main Content) -->
    <div class="col-span-2 space-y-6">
        
        <!-- 🔹 Ads Carousel -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Sponsored Ads</h3>
            <div id="adsCarousel" class="relative overflow-hidden">
                <div class="flex transition-transform duration-500" id="adsWrapper">
                    @foreach($ads as $ad)
                        <div class="min-w-full flex-shrink-0">
                            <img src="{{ $ad->image_url }}" alt="Ad" class="w-full rounded-lg">
                        </div>
                    @endforeach
                </div>
                <!-- Carousel controls -->
                <button onclick="prevAd()" class="absolute left-0 top-1/2 -translate-y-1/2 bg-gray-800 text-white px-2 py-1 rounded">‹</button>
                <button onclick="nextAd()" class="absolute right-0 top-1/2 -translate-y-1/2 bg-gray-800 text-white px-2 py-1 rounded">›</button>
            </div>
        </div>

        <!-- 🔹 Active Listings -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Active Property Listings (Other Landlords/Agents)</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Location</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Listed By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeListings as $listing)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $listing->name }}</td>
                            <td class="px-4 py-2">{{ $listing->location }}</td>
                            <td class="px-4 py-2">${{ number_format($listing->price) }}</td>
                            <td class="px-4 py-2">{{ $listing->user->name ?? 'Agent' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No active listings available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🔹 Active Rentals -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Active Rentals (Other Landlords)</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Tenant</th>
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Unit</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeRentals as $rental)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $rental->tenant->name }}</td>
                            <td class="px-4 py-2">{{ $rental->unit->property->name }}</td>
                            <td class="px-4 py-2">{{ $rental->unit->unit_number }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">{{ ucfirst($rental->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No active rentals found.</td></tr>
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

@push('scripts')
<script>
let currentAd = 0;
const adsWrapper = document.getElementById('adsWrapper');
const totalAds = adsWrapper.children.length;

function showAd(index) {
    currentAd = (index + totalAds) % totalAds;
    adsWrapper.style.transform = `translateX(-${currentAd * 100}%)`;
}

function nextAd() { showAd(currentAd + 1); }
function prevAd() { showAd(currentAd - 1); }

setInterval(nextAd, 5000); // auto slide every 5s
</script>
@endpush
