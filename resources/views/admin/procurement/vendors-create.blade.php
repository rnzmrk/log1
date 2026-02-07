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
                    <span class="ml-1 text-gray-500 md:ml-2">Add Vendor</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Add New Vendor</h1>
        <a href="{{ route('procurement.vendors') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Vendors
        </a>
    </div>

    <!-- Simple Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('vendors.store') }}">
            @csrf
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg m-6">
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

            <div class="p-6">
                <!-- Basic Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Vendor Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               required
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                        <input type="text" 
                               name="contact_person" 
                               id="contact_person" 
                               value="{{ old('contact_person') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" 
                                id="category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Category</option>
                            @foreach(config('categories.supplier_categories') as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" 
                                id="status" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Status</option>
                            <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Suspended" {{ old('status') === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="Under Review" {{ old('status') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address Field -->
                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" 
                              id="address" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Fields Table -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700">Value</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">City</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" 
                                               name="city" 
                                               id="city" 
                                               value="{{ old('city') }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('city')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">State</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" 
                                               name="state" 
                                               id="state" 
                                               value="{{ old('state') }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('state')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Postal Code</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" 
                                               name="postal_code" 
                                               id="postal_code" 
                                               value="{{ old('postal_code') }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('postal_code')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Country</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" 
                                               name="country" 
                                               id="country" 
                                               value="{{ old('country') }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('country')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Tax ID</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" 
                                               name="tax_id" 
                                               id="tax_id" 
                                               value="{{ old('tax_id') }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('tax_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Payment Terms</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" 
                                               name="payment_terms" 
                                               id="payment_terms" 
                                               value="{{ old('payment_terms') }}"
                                               placeholder="e.g., Net 30, Net 60"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('payment_terms')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Bank Name</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" 
                                               name="bank_name" 
                                               id="bank_name" 
                                               value="{{ old('bank_name') }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('bank_name')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Bank Account</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" 
                                               name="bank_account" 
                                               id="bank_account" 
                                               value="{{ old('bank_account') }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('bank_account')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Rating</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="number" 
                                               name="rating" 
                                               id="rating" 
                                               min="0" 
                                               max="5" 
                                               step="0.01"
                                               value="{{ old('rating') }}"
                                               placeholder="0.00 - 5.00"
                                               class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('rating')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Services</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <textarea name="services" 
                                                  id="services" 
                                                  rows="2"
                                                  placeholder="e.g., IT Support, Hardware Supply, Software Development"
                                                  class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('services') }}</textarea>
                                        @error('services')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Notes</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <textarea name="notes" 
                                                  id="notes" 
                                                  rows="2"
                                                  class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('procurement.vendors') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Save Vendor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
