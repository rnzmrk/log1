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
                    <span class="ml-1 text-gray-500 md:ml-2">Verifications</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Supplier Verifications</h1>
            <p class="text-gray-600 mt-1">Manage verification processes for {{ $supplier->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('vendors.verifications.create', $supplier->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus text-xl'></i>
                Schedule Verification
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

    <!-- Verifications Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verification Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verification Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($verifications as $verification)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ config('categories.verification_types.' . $verification->verification_type, $verification->verification_type) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($verification->status === 'scheduled' && $verification->scheduled_at)
                                    {{ $verification->scheduled_at->format('M d, Y H:i') }}
                                    @if($verification->isOverdue())
                                        <span class="ml-2 text-xs text-red-600 font-medium">⚠️ Overdue</span>
                                    @endif
                                @else
                                    {{ $verification->verification_date ? $verification->verification_date->format('M d, Y') : 'N/A' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $verification->status_color }}-100 text-{{ $verification->status_color }}-800">
                                    {{ ucfirst($verification->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($verification->score)
                                    <div class="flex items-center">
                                        <span class="font-medium text-{{ $verification->score_color }}-600">{{ $verification->score }}/100</span>
                                    </div>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($verification->score)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $verification->score_color }}-100 text-{{ $verification->score_color }}-800">
                                        {{ $verification->grade }}
                                    </span>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $verification->verifier ? $verification->verifier->name : 'Not verified' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('vendors.verifications.edit', [$supplier->id, $verification->id]) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                    @if($verification->status === 'scheduled')
                                        <form action="{{ route('vendors.verifications.complete', [$supplier->id, $verification->id]) }}" method="POST" class="inline" onsubmit="return confirm('Complete this verification?')">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Complete">
                                                <i class='bx bx-check-circle text-lg'></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('vendors.verifications.destroy', [$supplier->id, $verification->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this verification?')" class="inline">
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
                                    <i class='bx bx-check-shield text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No verifications found</p>
                                    <p class="text-sm mt-1">Schedule verification processes for this supplier.</p>
                                    <a href="{{ route('vendors.verifications.create', $supplier->id) }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                        <i class='bx bx-plus mr-2'></i>
                                        Schedule Verification
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
