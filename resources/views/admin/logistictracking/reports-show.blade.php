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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Logistic Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.logistictracking.reports') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Reports</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ $report->report_name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $report->report_name }}</h1>
            <p class="text-gray-600 mt-1">Report ID: {{ $report->report_number }}</p>
        </div>
        <div class="flex gap-3">
            @if($report->status === 'Completed')
                <a href="#" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-download text-xl'></i>
                    Download Report
                </a>
            @endif
            @if(in_array($report->status, ['Processing', 'Scheduled']))
                <a href="{{ route('logistics-reports.edit', $report->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-edit text-xl'></i>
                    Edit Report
                </a>
            @endif
            <a href="{{ route('admin.logistictracking.reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Report Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Report Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Report Type</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $report->type_color }}-100 text-{{ $report->type_color }}-800">
                                    {{ $report->report_type }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Status</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $report->status_color }}-100 text-{{ $report->status_color }}-800">
                                    {{ $report->status }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Priority</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $report->priority_color }}-100 text-{{ $report->priority_color }}-800">
                                    {{ $report->priority }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Department</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $report->department }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Generated By</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $report->generated_by }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Report Date</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $report->report_date->format('F d, Y') }}</p>
                        </div>
                        @if($report->start_date)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Start Date</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $report->start_date->format('F d, Y') }}</p>
                        </div>
                        @endif
                        @if($report->end_date)
                        <div>
                            <p class="text-sm font-medium text-gray-600">End Date</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $report->end_date->format('F d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if($report->description)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600">Description</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $report->description }}</p>
                    </div>
                    @endif
                    
                    @if($report->notes)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600">Additional Notes</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $report->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Report Data -->
            @if($report->status === 'Completed')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Report Data</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ $report->total_records }}</p>
                            <p class="text-sm text-gray-600">Total Records</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $report->success_rate }}%</p>
                            <p class="text-sm text-gray-600">Success Rate</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-purple-600">{{ $report->created_at->diffInDays() }}</p>
                            <p class="text-sm text-gray-600">Days Ago</p>
                        </div>
                    </div>
                    
                    @if($report->data_summary)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600 mb-3">Data Summary</p>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @foreach($report->data_summary as $key => $value)
                            <div class="flex justify-between py-2 border-b border-gray-200 last:border-0">
                                <span class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $value }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if($report->status === 'Completed')
                            <a href="#" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                <i class='bx bx-download text-xl'></i>
                                Download PDF
                            </a>
                            <a href="#" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                <i class='bx bx-file text-xl'></i>
                                Download Excel
                            </a>
                        @endif
                        <a href="#" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                            <i class='bx bx-share text-xl'></i>
                            Share Report
                        </a>
                        @if(in_array($report->status, ['Processing', 'Scheduled']))
                            <a href="{{ route('logistics-reports.edit', $report->id) }}" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                <i class='bx bx-edit text-xl'></i>
                                Edit Report
                            </a>
                        @endif
                        @if(in_array($report->status, ['Processing', 'Scheduled', 'Failed']))
                            <form action="{{ route('logistics-reports.destroy', $report->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors" onclick="return confirm('Are you sure you want to delete this report?')">
                                    <i class='bx bx-trash text-xl'></i>
                                    Delete Report
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Report Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="bg-blue-100 rounded-full p-2 mt-1">
                                <i class='bx bx-plus text-blue-600 text-sm'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Report Created</p>
                                <p class="text-xs text-gray-600">{{ $report->created_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($report->status === 'Completed')
                        <div class="flex items-start gap-3">
                            <div class="bg-green-100 rounded-full p-2 mt-1">
                                <i class='bx bx-check text-green-600 text-sm'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Report Completed</p>
                                <p class="text-xs text-gray-600">{{ $report->updated_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($report->status === 'Processing')
                        <div class="flex items-start gap-3">
                            <div class="bg-orange-100 rounded-full p-2 mt-1">
                                <i class='bx bx-loader text-orange-600 text-sm animate-spin'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Processing</p>
                                <p class="text-xs text-gray-600">Report is being generated...</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
