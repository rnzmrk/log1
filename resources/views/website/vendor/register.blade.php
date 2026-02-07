@extends('website.layouts.app')

@section('title', 'Become a Vendor - Join Our Supplier Network')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                <i class='bx bx-store text-white text-3xl'></i>
            </div>
            <h1 class="mt-6 text-4xl font-bold text-gray-900">Become Our Supplier</h1>
            <p class="mt-4 text-xl text-gray-600">Join our network of trusted suppliers and reach thousands of customers</p>
            
            <!-- Benefits -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-group text-blue-600 text-xl'></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Wide Reach</h3>
                    <p class="mt-2 text-gray-600">Connect with thousands of potential customers</p>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-trending-up text-green-600 text-xl'></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Grow Business</h3>
                    <p class="mt-2 text-gray-600">Increase your sales and expand your market</p>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-shield text-purple-600 text-xl'></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Trusted Platform</h3>
                    <p class="mt-2 text-gray-600">Secure and reliable supplier management</p>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Supplier Registration</h2>
                <p class="mt-2 text-blue-100">Fill in your business details to get started</p>
            </div>

            <form method="POST" action="{{ route('website.suppliers.register.submit') }}" enctype="multipart/form-data" class="p-8">
                @csrf
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx bx-error-circle text-red-400 text-xl'></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Registration failed</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Company Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class='bx bx-building text-blue-600 mr-2'></i>
                        Company Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., Tech Supplies Inc.">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Supplier Category *</label>
                            <select id="category" 
                                    name="category" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select your primary category</option>
                                <option value="Computer Supplier" {{ old('category') === 'Computer Supplier' ? 'selected' : '' }}>Computer Supplier</option>
                                <option value="Office Supplies Supplier" {{ old('category') === 'Office Supplies Supplier' ? 'selected' : '' }}>Office Supplies Supplier</option>
                                <option value="Bond Paper Supplier" {{ old('category') === 'Bond Paper Supplier' ? 'selected' : '' }}>Bond Paper Supplier</option>
                                <option value="Cellphone Supplier" {{ old('category') === 'Cellphone Supplier' ? 'selected' : '' }}>Cellphone Supplier</option>
                                <option value="Furniture Supplier" {{ old('category') === 'Furniture Supplier' ? 'selected' : '' }}>Furniture Supplier</option>
                                <option value="Equipment Supplier" {{ old('category') === 'Equipment Supplier' ? 'selected' : '' }}>Equipment Supplier</option>
                                <option value="Software Supplier" {{ old('category') === 'Software Supplier' ? 'selected' : '' }}>Software Supplier</option>
                                <option value="Printing Services" {{ old('category') === 'Printing Services' ? 'selected' : '' }}>Printing Services</option>
                                <option value="IT Services" {{ old('category') === 'IT Services' ? 'selected' : '' }}>IT Services</option>
                                <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class='bx bx-user text-blue-600 mr-2'></i>
                        Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person *</label>
                            <input type="text" 
                                   id="contact_person" 
                                   name="contact_person" 
                                   value="{{ old('contact_person') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., John Smith">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., john@company.com">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., +1-555-0123">
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" 
                                   id="website" 
                                   name="website" 
                                   value="{{ old('website') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., https://www.company.com">
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class='bx bx-map text-blue-600 mr-2'></i>
                        Address Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                            <input type="text" 
                                   id="address" 
                                   name="address" 
                                   value="{{ old('address') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., 123 Business Street, Suite 100">
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="{{ old('city') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., New York">
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State/Province *</label>
                            <input type="text" 
                                   id="state" 
                                   name="state" 
                                   value="{{ old('state') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., NY">
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                            <input type="text" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   value="{{ old('postal_code') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., 10001">
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                            <input type="text" 
                                   id="country" 
                                   name="country" 
                                   value="{{ old('country') ?? 'United States' }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., United States">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <i class='bx bx-info-circle mr-1'></i>
                        By submitting this form, you agree to our terms and conditions
                    </div>
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class='bx bx-send mr-2'></i>
                        Submit Registration
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        <div class="mt-8 text-center">
            <p class="text-gray-600">
                <i class='bx bx-check-circle text-green-500 mr-1'></i>
                Your registration will be reviewed by our team. You'll receive a confirmation email once approved.
            </p>
        </div>
    </div>
</div>
@endsection
