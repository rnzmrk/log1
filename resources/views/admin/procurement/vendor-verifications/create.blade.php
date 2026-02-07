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
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('vendors.show', $supplier->id) }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">{{ $supplier->name }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('vendors.verifications.index', $supplier->id) }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Verifications</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Schedule Verification</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Schedule Verification</h1>
            <p class="text-gray-600 mt-1">Schedule verification process for {{ $supplier->name }}</p>
        </div>
        <a href="{{ route('vendors.verifications.index', $supplier->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Verifications
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('vendors.verifications.store', $supplier->id) }}">
            @csrf
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Verification Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="verification_type" class="block text-sm font-medium text-gray-700 mb-2">Verification Type *</label>
                            <select name="verification_type" 
                                    id="verification_type" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Verification Type</option>
                                @foreach($verificationTypes as $key => $type)
                                    <option value="{{ $key }}" {{ old('verification_type') === $key ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('verification_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="verification_date" class="block text-sm font-medium text-gray-700 mb-2">Verification Date *</label>
                            <input type="date" 
                                   name="verification_date" 
                                   id="verification_date" 
                                   value="{{ old('verification_date') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('verification_date')
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
                                <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="passed" {{ old('status') === 'passed' ? 'selected' : '' }}>Passed</option>
                                <option value="failed" {{ old('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="score" class="block text-sm font-medium text-gray-700 mb-2">Score (0-100)</label>
                            <input type="number" 
                                   name="score" 
                                   id="score" 
                                   value="{{ old('score') }}"
                                   min="0" 
                                   max="100"
                                   placeholder="e.g., 85"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="report_path" class="block text-sm font-medium text-gray-700 mb-2">Report Path</label>
                            <input type="text" 
                                   name="report_path" 
                                   id="report_path" 
                                   value="{{ old('report_path') }}"
                                   placeholder="e.g., /reports/verifications/site-visit.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('report_path')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="findings" class="block text-sm font-medium text-gray-700 mb-2">Findings</label>
                            <textarea name="findings" 
                                      id="findings" 
                                      rows="3"
                                      placeholder="Detailed findings from the verification..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('findings') }}</textarea>
                            @error('findings')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-2">Recommendations</label>
                            <textarea name="recommendations" 
                                      id="recommendations" 
                                      rows="3"
                                      placeholder="Recommendations based on verification results..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('recommendations') }}</textarea>
                            @error('recommendations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Supplier Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Supplier Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $supplier->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Vendor Code:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $supplier->vendor_code }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Contact:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $supplier->contact_person ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $supplier->email ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('vendors.verifications.index', $supplier->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Schedule Verification
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
