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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Document Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.documenttracking.validation') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Validation</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="text-gray-500 ml-1 md:ml-2">Supplier Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Supplier Validation Details</h1>
            <p class="text-gray-600 mt-1">Complete supplier information and validation status</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.documenttracking.validation') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Validation
            </a>
            @if ($supplier->validation_status === 'Pending' || $supplier->validation_status === 'Under Review')
                <a href="{{ route('admin.documenttracking.edit-validation', $supplier->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-check-circle text-xl'></i>
                    Validate Supplier
                </a>
            @endif
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class='bx bx-check-circle text-xl mr-2'></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Supplier Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Supplier Information</h2>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        @if ($supplier->status === 'Active') bg-green-100 text-green-800
                        @elseif ($supplier->status === 'Pending') bg-orange-100 text-orange-800
                        @elseif ($supplier->status === 'Suspended') bg-red-100 text-red-800
                        @elseif ($supplier->status === 'Under Review') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $supplier->status }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Name</label>
                        <p class="text-gray-900 font-medium">{{ $supplier->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor Code</label>
                        <p class="text-gray-900">{{ $supplier->vendor_code ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                        <p class="text-gray-900 font-medium">{{ $supplier->contact_person }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <p class="text-gray-900">{{ $supplier->category ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <p class="text-gray-900">{{ $supplier->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <p class="text-gray-900">{{ $supplier->phone }}</p>
                    </div>
                </div>

                @if ($supplier->address)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <p class="text-gray-900">{{ $supplier->address }}</p>
                        @if ($supplier->city || $supplier->state || $supplier->postal_code)
                            <p class="text-gray-900">
                                {{ $supplier->city ?? '' }}{{ $supplier->city && $supplier->state ? ', ' : '' }}{{ $supplier->state ?? '' }}{{ ($supplier->city || $supplier->state) && $supplier->postal_code ? ' ' : '' }}{{ $supplier->postal_code ?? '' }}
                            </p>
                        @endif
                    </div>
                @endif

                @if ($supplier->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <p class="text-gray-900">{{ $supplier->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Business Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Business Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Business Registration Number</label>
                        <p class="text-gray-900">{{ $supplier->business_registration ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax Identification Number</label>
                        <p class="text-gray-900">{{ $supplier->tax_id ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <p class="text-gray-900">{{ $supplier->website ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Years in Business</label>
                        <p class="text-gray-900">{{ $supplier->years_in_business ?? 'N/A' }}</p>
                    </div>
                </div>

                @if ($supplier->services_offered)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Services Offered</label>
                        <p class="text-gray-900">{{ $supplier->services_offered }}</p>
                    </div>
                @endif
            </div>

            <!-- Validation Notes -->
            @if ($supplier->validation_notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Validation Notes</h2>
                    <p class="text-gray-900">{{ $supplier->validation_notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Validation Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Validation Status</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Current Status:</span>
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if ($supplier->status === 'Active') bg-green-100 text-green-800
                            @elseif ($supplier->status === 'Pending') bg-orange-100 text-orange-800
                            @elseif ($supplier->status === 'Suspended') bg-red-100 text-red-800
                            @elseif ($supplier->status === 'Under Review') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $supplier->status }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Submitted:</span>
                        <span class="text-sm text-gray-900">{{ $supplier->created_at->format('M d, Y') }}</span>
                    </div>

                    @if ($supplier->updated_at && $supplier->updated_at != $supplier->created_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Last Updated:</span>
                            <span class="text-sm text-gray-900">{{ $supplier->updated_at->format('M d, Y') }}</span>
                        </div>
                    @endif

                    @if ($supplier->created_by)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Created By:</span>
                            <span class="text-sm text-gray-900">{{ $supplier->created_by }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            @if ($supplier->documents)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Submitted Documents</h3>
                    <div class="space-y-2">
                        @foreach ($supplier->documents as $document)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-700">{{ $document }}</span>
                                <i class='bx bx-file text-gray-400'></i>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    @if ($supplier->validation_status === 'Pending' || $supplier->validation_status === 'Under Review')
                        <a href="{{ route('admin.documenttracking.edit-validation', $supplier->id) }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                            <i class='bx bx-check-circle text-xl'></i>
                            Validate Supplier
                        </a>
                    @endif

                    @if ($supplier->validation_status === 'Validated')
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                            <i class='bx bx-printer text-xl'></i>
                            Print Certificate
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
