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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Admin Settings</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.adminsettings.audit-logs') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Audit Logs</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Log Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Audit Log Details</h1>
        <div class="flex gap-3">
            <a href="{{ route('audit-logs.export') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-download text-xl'></i>
                Export
            </a>
            <a href="{{ route('admin.adminsettings.audit-logs') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Logs
            </a>
        </div>
    </div>

    <!-- Log Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">System Activity Details</h2>
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-{{ $auditLog->status_color }}-100 text-{{ $auditLog->status_color }}-800">
                {{ $auditLog->status }}
            </span>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Information -->
                <div class="space-y-4">
                    <h3 class="text-md font-semibold text-gray-900">User Information</h3>
                    
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full bg-{{ $auditLog->avatar_color }}-600 flex items-center justify-center text-white font-bold text-xl">
                            {{ $auditLog->initials }}
                        </div>
                        <div>
                            <p class="text-lg font-medium text-gray-900">{{ $auditLog->user_name }}</p>
                            <p class="text-sm text-gray-500">{{ $auditLog->user_email }}</p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $auditLog->module_color }}-100 text-{{ $auditLog->module_color }}-800">
                                {{ $auditLog->module }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Activity Information -->
                <div class="space-y-4">
                    <h3 class="text-md font-semibold text-gray-900">Activity Information</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Action</p>
                            <p class="text-sm text-gray-900 font-semibold">{{ $auditLog->action }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Timestamp</p>
                            <p class="text-sm text-gray-900">{{ $auditLog->created_at->format('M d, Y H:i:s A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">IP Address</p>
                            <p class="text-sm text-gray-900">{{ $auditLog->ip_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Details -->
            <div class="mt-6">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Technical Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">User Agent</p>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $auditLog->user_agent ?? 'Not available' }}</p>
                    </div>
                    
                    @if($auditLog->details)
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">Additional Details</p>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <pre class="text-sm text-gray-900 whitespace-pre-wrap">{{ is_array($auditLog->details) ? json_encode($auditLog->details, JSON_PRETTY_PRINT) : $auditLog->details }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.adminsettings.audit-logs') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to Logs
                </a>
                <a href="{{ route('audit-logs.export') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-download mr-2'></i>
                    Export Logs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
