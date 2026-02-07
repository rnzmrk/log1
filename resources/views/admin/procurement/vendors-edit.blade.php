@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                    <i class='bx bx-home text-xl mr-2'></i>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Procurement</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('procurement.vendors') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Vendors</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Vendor</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Vendor</h1>
            <p class="text-gray-600 mt-1">Update vendor information and status</p>
        </div>
        <a href="{{ route('procurement.vendors') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Vendors
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Vendor Information</h2>
        </div>
        
        <form method="POST" action="{{ route('vendors.update', $supplier->id) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center mb-2">
                        <i class='bx bx-error-circle text-xl mr-2'></i>
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-md font-semibold text-gray-900 border-b pb-2">Basic Information</h3>
                    
                    <!-- Company Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $supplier->name) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Vendor Code -->
                    <div>
                        <label for="vendor_code" class="block text-sm font-medium text-gray-700 mb-2">Vendor Code *</label>
                        <input type="text" 
                               id="vendor_code" 
                               name="vendor_code" 
                               value="{{ old('vendor_code', $supplier->vendor_code) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select id="category" 
                                name="category" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select category</option>
                            <option value="Computer Supplier" {{ old('category', $supplier->category) === 'Computer Supplier' ? 'selected' : '' }}>Computer Supplier</option>
                            <option value="Office Supplies" {{ old('category', $supplier->category) === 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                            <option value="Cellphone Supplier" {{ old('category', $supplier->category) === 'Cellphone Supplier' ? 'selected' : '' }}>Cellphone Supplier</option>
                            <option value="Furniture Supplier" {{ old('category', $supplier->category) === 'Furniture Supplier' ? 'selected' : '' }}>Furniture Supplier</option>
                            <option value="Equipment Supplier" {{ old('category', $supplier->category) === 'Equipment Supplier' ? 'selected' : '' }}>Equipment Supplier</option>
                            <option value="Software Supplier" {{ old('category', $supplier->category) === 'Software Supplier' ? 'selected' : '' }}>Software Supplier</option>
                            <option value="Printing Services" {{ old('category', $supplier->category) === 'Printing Services' ? 'selected' : '' }}>Printing Services</option>
                            <option value="IT Services" {{ old('category', $supplier->category) === 'IT Services' ? 'selected' : '' }}>IT Services</option>
                            <option value="General" {{ old('category', $supplier->category) === 'General' ? 'selected' : '' }}>General</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select id="status" 
                                name="status" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="Pending" {{ old('status', $supplier->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Under Review" {{ old('status', $supplier->status) === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                            <option value="Active" {{ old('status', $supplier->status) === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $supplier->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Suspended" {{ old('status', $supplier->status) === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <!-- Rating -->
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <input type="number" 
                               id="rating" 
                               name="rating" 
                               value="{{ old('rating', $supplier->rating) }}"
                               min="0" 
                               max="5" 
                               step="0.1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-4">
                    <h3 class="text-md font-semibold text-gray-900 border-b pb-2">Contact Information</h3>
                    
                    <!-- Contact Person -->
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                        <input type="text" 
                               id="contact_person" 
                               name="contact_person" 
                               value="{{ old('contact_person', $supplier->contact_person) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $supplier->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $supplier->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $supplier->address) }}</textarea>
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" 
                               id="website" 
                               name="website" 
                               value="{{ old('website', $supplier->website) }}"
                               placeholder="https://example.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-6 space-y-4">
                <h3 class="text-md font-semibold text-gray-900 border-b pb-2">Additional Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $supplier->description) }}</textarea>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('procurement.vendors') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Vendor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
