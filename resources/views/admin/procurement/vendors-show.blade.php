@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h4 class="text-2xl font-bold mb-1">Supplier Details</h4>
                <nav aria-label="breadcrumb">
                    <ol class="flex items-center text-sm">
                        <li class="mr-2">
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                        </li>
                        <li class="mr-2 text-gray-500">/</li>
                        <li class="mr-2">
                            <a href="{{ route('procurement.vendors') }}" class="text-blue-600 hover:text-blue-800">Suppliers</a>
                        </li>
                        <li class="mr-2 text-gray-500">/</li>
                        <li class="font-semibold text-gray-700">Details</li>
                    </ol>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('vendors.edit', $supplier->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class='bx bx-edit mr-2'></i>
                    Edit Supplier
                </a>
                <a href="{{ route('procurement.vendors') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg flex items-center">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to Suppliers
                </a>
            </div>
        </div>
    </div>

    <!-- Supplier Information Card -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h5 class="text-lg font-semibold mb-0">Supplier Information</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <p class="text-gray-900">{{ $supplier->company_name ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                            <p class="text-gray-900">{{ $supplier->contact_person ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <p class="text-gray-900">{{ $supplier->email ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <p class="text-gray-900">{{ $supplier->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <p class="text-gray-900">{{ $supplier->address ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <p class="text-gray-900">{{ $supplier->city ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <p class="text-gray-900">{{ $supplier->country ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <p>
                                @if($supplier->status === 'Active')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $supplier->status }}</span>
                                @elseif($supplier->status === 'Inactive')
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">{{ $supplier->status }}</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">{{ $supplier->status }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($supplier->description)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <p class="text-gray-900">{{ $supplier->description }}</p>
                </div>
                @endif

                <hr class="my-6 border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Created On</label>
                        <p class="text-gray-900">{{ $supplier->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($supplier->updated_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Last Updated</label>
                        <p class="text-gray-900">{{ $supplier->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Card -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h5 class="text-lg font-semibold mb-0">Actions</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('vendors.edit', $supplier->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg flex items-center justify-center">
                        <i class='bx bx-edit mr-2'></i>
                        Edit Supplier
                    </a>
                    
                    @if($supplier->status !== 'Active')
                    <form method="POST" action="{{ route('vendors.approve', $supplier->id) }}">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg flex items-center justify-center w-full">
                            <i class='bx bx-check mr-2'></i>
                            Approve Supplier
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('vendors.validations.index', $supplier->id) }}" class="border border-blue-300 text-blue-600 hover:bg-blue-50 px-4 py-3 rounded-lg flex items-center justify-center">
                        <i class='bx bx-file-check mr-2'></i>
                        View Validations
                    </a>
                    
                    <a href="{{ route('vendors.verifications.index', $supplier->id) }}" class="border border-yellow-300 text-yellow-600 hover:bg-yellow-50 px-4 py-3 rounded-lg flex items-center justify-center">
                        <i class='bx bx-shield-check mr-2'></i>
                        View Verifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

