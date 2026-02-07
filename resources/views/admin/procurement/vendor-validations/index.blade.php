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
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Validations</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Supplier Validations</h1>
            <p class="text-gray-600 mt-1">Manage document validations for {{ $supplier->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('vendors.validations.create', $supplier->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus text-xl'></i>
                Add Validation
            </a>
            <a href="{{ route('procurement.vendors') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Vendors
            </a>
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

    <!-- Validations Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validated By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($validations as $validation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ config('categories.validation_types.' . $validation->validation_type, $validation->validation_type) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $validation->document_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $validation->issue_date ? $validation->issue_date->format('M d, Y') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $validation->expiry_date ? $validation->expiry_date->format('M d, Y') : 'N/A' }}
                                @if($validation->isExpiringSoon())
                                    <span class="ml-2 text-xs text-orange-600 font-medium">⚠️ Expiring Soon</span>
                                @elseif($validation->isExpired())
                                    <span class="ml-2 text-xs text-red-600 font-medium">❌ Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $validation->status_color }}-100 text-{{ $validation->status_color }}-800">
                                    {{ ucfirst($validation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $validation->validator ? $validation->validator->name : 'Not validated' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('vendors.validations.edit', [$supplier->id, $validation->id]) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                    @if($validation->status === 'pending')
                                        <form action="{{ route('vendors.validations.validate', [$supplier->id, $validation->id]) }}" method="POST" class="inline" onsubmit="return confirm('Validate this document?')">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Validate">
                                                <i class='bx bx-check-double text-lg'></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('vendors.validations.destroy', [$supplier->id, $validation->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this validation?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-certification text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No validations found</p>
                                    <p class="text-sm mt-1">Add document validations for this supplier.</p>
                                    <a href="{{ route('vendors.validations.create', $supplier->id) }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                        <i class='bx bx-plus mr-2'></i>
                                        Add Validation
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
