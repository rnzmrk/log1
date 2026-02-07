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
                    <a href="{{ route('project-planning.index') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Project Planning & Request</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">New Project</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Project Planning & Request</h1>
        <div class="flex gap-3">
            <a href="{{ route('project-planning.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-left text-xl'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Project Form Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Project Details</h2>
                </div>
                <form method="POST" action="{{ route('project-planning.store') }}" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class='bx bx-error text-red-400 text-xl'></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
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

                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Basic Information</h3>
                        
                        <!-- Project Name -->
                        <div>
                            <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                            <input type="text" 
                                   id="project_name"
                                   name="project_name" 
                                   value="{{ old('project_name') }}"
                                   placeholder="Enter project name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- Project Description -->
                        <div>
                            <label for="project_description" class="block text-sm font-medium text-gray-700 mb-2">Project Description *</label>
                            <textarea id="project_description"
                                      name="project_description" 
                                      rows="4"
                                      placeholder="Provide detailed description of the project..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      required>{{ old('project_description') }}</textarea>
                        </div>

                        <!-- Project Type and Priority -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="project_type" class="block text-sm font-medium text-gray-700 mb-2">Project Type *</label>
                                <select id="project_type"
                                        name="project_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="Construction" {{ old('project_type') === 'Construction' ? 'selected' : '' }}>Construction</option>
                                    <option value="Renovation" {{ old('project_type') === 'Renovation' ? 'selected' : '' }}>Renovation</option>
                                    <option value="Maintenance" {{ old('project_type') === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="Installation" {{ old('project_type') === 'Installation' ? 'selected' : '' }}>Installation</option>
                                    <option value="Inspection" {{ old('project_type') === 'Inspection' ? 'selected' : '' }}>Inspection</option>
                                    <option value="Other" {{ old('project_type') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                                <select id="priority"
                                        name="priority" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Select Priority</option>
                                    <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Critical" {{ old('priority') === 'Critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Project Timeline</h3>
                        
                        <!-- Start and End Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="date" 
                                       id="start_date"
                                       name="start_date"
                                       value="{{ old('start_date') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                <input type="date" 
                                       id="end_date"
                                       name="end_date"
                                       value="{{ old('end_date') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <!-- Estimated Duration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Duration</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <input type="number" 
                                           id="estimated_duration_days"
                                           name="estimated_duration_days"
                                           value="{{ old('estimated_duration_days') }}"
                                           placeholder="Days" 
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <input type="number" 
                                           id="estimated_duration_weeks"
                                           name="estimated_duration_weeks"
                                           value="{{ old('estimated_duration_weeks') }}"
                                           placeholder="Weeks" 
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <input type="number" 
                                           id="estimated_duration_months"
                                           name="estimated_duration_months"
                                           value="{{ old('estimated_duration_months') }}"
                                           placeholder="Months" 
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Location Information</h3>
                        
                        <!-- Project Address -->
                        <div>
                            <label for="project_address" class="block text-sm font-medium text-gray-700 mb-2">Project Address *</label>
                            <input type="text" 
                                   id="project_address"
                                   name="project_address"
                                   value="{{ old('project_address') }}"
                                   placeholder="Enter project address" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- City, State, ZIP -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="project_city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input type="text" 
                                       id="project_city"
                                       name="project_city"
                                       value="{{ old('project_city') }}"
                                       placeholder="City" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="project_state" class="block text-sm font-medium text-gray-700 mb-2">State/Province *</label>
                                <input type="text" 
                                       id="project_state"
                                       name="project_state"
                                       value="{{ old('project_state') }}"
                                       placeholder="State" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="project_zipcode" class="block text-sm font-medium text-gray-700 mb-2">ZIP/Postal Code *</label>
                                <input type="text" 
                                       id="project_zipcode"
                                       name="project_zipcode"
                                       value="{{ old('project_zipcode') }}"
                                       placeholder="ZIP Code" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <!-- Contact Person -->
                        <div>
                            <label for="onsite_contact_person" class="block text-sm font-medium text-gray-700 mb-2">On-site Contact Person *</label>
                            <input type="text" 
                                   id="onsite_contact_person"
                                   name="onsite_contact_person"
                                   value="{{ old('onsite_contact_person') }}"
                                   placeholder="Name and phone number" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>

                    <!-- Resource Requirements -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Resource Requirements</h3>
                        
                        <!-- Personnel -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Personnel Required</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Engineers</label>
                                    <input type="number" 
                                           id="engineers_required"
                                           name="engineers_required"
                                           value="{{ old('engineers_required') }}"
                                           min="0"
                                           placeholder="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Technicians</label>
                                    <input type="number" 
                                           id="technicians_required"
                                           name="technicians_required"
                                           value="{{ old('technicians_required') }}"
                                           min="0"
                                           placeholder="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Laborers</label>
                                    <input type="number" 
                                           id="laborers_required"
                                           name="laborers_required"
                                           value="{{ old('laborers_required') }}"
                                           min="0"
                                           placeholder="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Equipment -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Equipment Needed</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="needs_cranes"
                                           value="1"
                                           {{ old('needs_cranes') ? 'checked' : '' }}
                                           class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Cranes & Heavy Machinery</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="needs_power_tools"
                                           value="1"
                                           {{ old('needs_power_tools') ? 'checked' : '' }}
                                           class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Power Tools & Equipment</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="needs_safety_equipment"
                                           value="1"
                                           {{ old('needs_safety_equipment') ? 'checked' : '' }}
                                           class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Safety Equipment</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="needs_measurement_tools"
                                           value="1"
                                           {{ old('needs_measurement_tools') ? 'checked' : '' }}
                                           class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Measurement Tools</span>
                                </label>
                            </div>
                        </div>

                        <!-- Materials -->
                        <div>
                            <label for="materials_required" class="block text-sm font-medium text-gray-700 mb-2">Materials Required</label>
                            <textarea id="materials_required"
                                      name="materials_required" 
                                      rows="3"
                                      placeholder="List materials needed for the project..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('materials_required') }}</textarea>
                        </div>
                    </div>

                    <!-- Budget Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Budget Information</h3>
                        
                        <!-- Estimated Budget -->
                        <div>
                            <label for="estimated_budget" class="block text-sm font-medium text-gray-700 mb-2">Estimated Budget *</label>
                            <input type="number" 
                                   id="estimated_budget"
                                   name="estimated_budget"
                                   value="{{ old('estimated_budget') }}"
                                   placeholder="Enter estimated amount" 
                                   min="0"
                                   step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- Cost Breakdown -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="labor_cost" class="block text-sm font-medium text-gray-700 mb-2">Labor Cost</label>
                                <input type="number" 
                                       id="labor_cost"
                                       name="labor_cost"
                                       value="{{ old('labor_cost') }}"
                                       placeholder="0.00" 
                                       min="0"
                                       step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="material_cost" class="block text-sm font-medium text-gray-700 mb-2">Material Cost</label>
                                <input type="number" 
                                       id="material_cost"
                                       name="material_cost"
                                       value="{{ old('material_cost') }}"
                                       placeholder="0.00" 
                                       min="0"
                                       step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="equipment_rental_cost" class="block text-sm font-medium text-gray-700 mb-2">Equipment Rental</label>
                                <input type="number" 
                                       id="equipment_rental_cost"
                                       name="equipment_rental_cost"
                                       value="{{ old('equipment_rental_cost') }}"
                                       placeholder="0.00" 
                                       min="0"
                                       step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="other_expenses" class="block text-sm font-medium text-gray-700 mb-2">Other Expenses</label>
                                <input type="number" 
                                       id="other_expenses"
                                       name="other_expenses"
                                       value="{{ old('other_expenses') }}"
                                       placeholder="0.00" 
                                       min="0"
                                       step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Requester Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Requester Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="requested_by" class="block text-sm font-medium text-gray-700 mb-2">Requested By *</label>
                                <input type="text" 
                                       id="requested_by"
                                       name="requested_by"
                                       value="{{ old('requested_by', 'John Doe') }}"
                                       placeholder="Your name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                                <input type="text" 
                                       id="department"
                                       name="department"
                                       value="{{ old('department', 'Operations') }}"
                                       placeholder="Your department" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea id="notes"
                                      name="notes" 
                                      rows="3"
                                      placeholder="Any additional notes or special requirements..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('project-planning.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-x mr-2'></i>
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-send mr-2'></i>
                            Submit Project
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Project Summary</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Project ID</span>
                            <span class="text-sm font-medium text-gray-900">Auto-generated</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Requester</span>
                            <span class="text-sm font-medium text-gray-900">{{ old('requested_by', 'John Doe') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Department</span>
                            <span class="text-sm font-medium text-gray-900">{{ old('department', 'Operations') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Date</span>
                            <span class="text-sm font-medium text-gray-900">{{ now()->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Draft
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $recentProjects = \App\Models\ProjectPlanning::orderBy('created_at', 'desc')->limit(3)->get();
                        @endphp
                        @if($recentProjects->count() > 0)
                            @foreach($recentProjects as $recentProject)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm">{{ $recentProject->project_name }}</h4>
                                        <span class="text-xs 
                                            @if($recentProject->status === 'Approved') bg-green-100 text-green-800 
                                            @elseif($recentProject->status === 'Under Review') bg-orange-100 text-orange-800 
                                            @elseif($recentProject->status === 'Draft') bg-gray-100 text-gray-800 
                                            @else bg-blue-100 text-blue-800 @endif 
                                            px-2 py-1 rounded-full">{{ $recentProject->status }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600">ID: {{ $recentProject->project_number }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">No recent projects found.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Help & Support</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-help-circle text-blue-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Project Guidelines</p>
                                <p class="text-xs text-gray-600">View policies</p>
                            </div>
                        </button>
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-message text-green-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Contact Project Manager</p>
                                <p class="text-xs text-gray-600">Get assistance</p>
                            </div>
                        </button>
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-history text-orange-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">My Previous Projects</p>
                                <p class="text-xs text-gray-600">View history</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
