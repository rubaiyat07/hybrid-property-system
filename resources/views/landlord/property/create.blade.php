{{-- File: resources/views/landlord/property/create.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Add New Property')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Property</h1>
            <p class="text-gray-600 mt-1">Fill in the details to add a new property to your portfolio</p>
        </div>
        <a href="{{ route('landlord.property.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to Properties
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('landlord.property.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Property Information</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Property Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}" placeholder="e.g., Sunset Apartments" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('type') border-red-500 @enderror" required>
                        <option value="">Select Property Type</option>
                        <option value="apartment" {{ old('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="house" {{ old('type') == 'house' ? 'selected' : '' }}>House</option>
                        <option value="condo" {{ old('type') == 'condo' ? 'selected' : '' }}>Condo</option>
                        <option value="townhouse" {{ old('type') == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                        <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Street Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" id="address" rows="2"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('address') border-red-500 @enderror"
                              placeholder="Enter full street address" required>{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City, State, ZIP -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="city" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('city') border-red-500 @enderror"
                               value="{{ old('city') }}" placeholder="City" required>
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                            State <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="state" id="state" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('state') border-red-500 @enderror"
                               value="{{ old('state') }}" placeholder="State" required>
                        @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-1">
                            ZIP Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="zip_code" id="zip_code" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('zip_code') border-red-500 @enderror"
                               value="{{ old('zip_code') }}" placeholder="ZIP Code" required>
                        @error('zip_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Describe the property features, amenities, etc.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Image
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 2MB)</p>
                            </div>
                            <input type="file" name="image" id="image" class="hidden" accept="image/*">
                        </label>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 rounded-b-lg">
                <a href="{{ route('landlord.property.index') }}" 
                   class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium">
                    <i class="fas fa-save mr-1"></i> Create Property
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview functionality here if needed
            console.log('File selected:', file.name);
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush

@endsection