<?php

namespace App\Http\Controllers\LogisticsTracking;

use App\Http\Controllers\Controller;
use App\Models\ProjectPlanning;
use Illuminate\Http\Request;

class ProjectPlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProjectPlanning::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('project_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('project_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('project_description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('requested_by', 'like', '%' . $searchTerm . '%')
                  ->orWhere('project_address', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by project type
        if ($request->filled('project_type')) {
            $query->where('project_type', $request->project_type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.logistictracking.project-planning-list', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.logistictracking.project-planning-request');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string',
            'project_type' => 'required|in:Construction,Renovation,Maintenance,Installation,Inspection,Other',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'estimated_duration_days' => 'nullable|integer|min:0',
            'estimated_duration_weeks' => 'nullable|integer|min:0',
            'estimated_duration_months' => 'nullable|integer|min:0',
            'project_address' => 'required|string|max:255',
            'project_city' => 'required|string|max:255',
            'project_state' => 'required|string|max:255',
            'project_zipcode' => 'required|string|max:255',
            'onsite_contact_person' => 'required|string|max:255',
            'engineers_required' => 'nullable|integer|min:0',
            'technicians_required' => 'nullable|integer|min:0',
            'laborers_required' => 'nullable|integer|min:0',
            'needs_cranes' => 'nullable|boolean',
            'needs_power_tools' => 'nullable|boolean',
            'needs_safety_equipment' => 'nullable|boolean',
            'needs_measurement_tools' => 'nullable|boolean',
            'materials_required' => 'nullable|string',
            'estimated_budget' => 'required|numeric|min:0|max:999999999999.99',
            'labor_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'material_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'equipment_rental_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'other_expenses' => 'nullable|numeric|min:0|max:999999999999.99',
            'requested_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Set default values for checkboxes
        $validated['needs_cranes'] = $request->has('needs_cranes');
        $validated['needs_power_tools'] = $request->has('needs_power_tools');
        $validated['needs_safety_equipment'] = $request->has('needs_safety_equipment');
        $validated['needs_measurement_tools'] = $request->has('needs_measurement_tools');

        // Set default values for duration
        $validated['estimated_duration_days'] = $validated['estimated_duration_days'] ?? 0;
        $validated['estimated_duration_weeks'] = $validated['estimated_duration_weeks'] ?? 0;
        $validated['estimated_duration_months'] = $validated['estimated_duration_months'] ?? 0;

        // Set default values for personnel
        $validated['engineers_required'] = $validated['engineers_required'] ?? 0;
        $validated['technicians_required'] = $validated['technicians_required'] ?? 0;
        $validated['laborers_required'] = $validated['laborers_required'] ?? 0;

        // Set default values for costs
        $validated['labor_cost'] = $validated['labor_cost'] ?? 0;
        $validated['material_cost'] = $validated['material_cost'] ?? 0;
        $validated['equipment_rental_cost'] = $validated['equipment_rental_cost'] ?? 0;
        $validated['other_expenses'] = $validated['other_expenses'] ?? 0;

        ProjectPlanning::create($validated);

        return redirect()->route('project-planning.index')
            ->with('success', 'Project planning request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = ProjectPlanning::findOrFail($id);
        return view('admin.logistictracking.project-planning-show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = ProjectPlanning::findOrFail($id);
        return view('admin.logistictracking.project-planning-edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = ProjectPlanning::findOrFail($id);

        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string',
            'project_type' => 'required|in:Construction,Renovation,Maintenance,Installation,Inspection,Other',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'status' => 'required|in:Draft,Submitted,Under Review,Approved,In Progress,On Hold,Completed,Cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'estimated_duration_days' => 'nullable|integer|min:0',
            'estimated_duration_weeks' => 'nullable|integer|min:0',
            'estimated_duration_months' => 'nullable|integer|min:0',
            'project_address' => 'required|string|max:255',
            'project_city' => 'required|string|max:255',
            'project_state' => 'required|string|max:255',
            'project_zipcode' => 'required|string|max:255',
            'onsite_contact_person' => 'required|string|max:255',
            'engineers_required' => 'nullable|integer|min:0',
            'technicians_required' => 'nullable|integer|min:0',
            'laborers_required' => 'nullable|integer|min:0',
            'needs_cranes' => 'nullable|boolean',
            'needs_power_tools' => 'nullable|boolean',
            'needs_safety_equipment' => 'nullable|boolean',
            'needs_measurement_tools' => 'nullable|boolean',
            'materials_required' => 'nullable|string',
            'estimated_budget' => 'required|numeric|min:0|max:999999999999.99',
            'labor_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'material_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'equipment_rental_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'other_expenses' => 'nullable|numeric|min:0|max:999999999999.99',
            'requested_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Set checkbox values
        $validated['needs_cranes'] = $request->has('needs_cranes');
        $validated['needs_power_tools'] = $request->has('needs_power_tools');
        $validated['needs_safety_equipment'] = $request->has('needs_safety_equipment');
        $validated['needs_measurement_tools'] = $request->has('needs_measurement_tools');

        // Set default values
        $validated['estimated_duration_days'] = $validated['estimated_duration_days'] ?? 0;
        $validated['estimated_duration_weeks'] = $validated['estimated_duration_weeks'] ?? 0;
        $validated['estimated_duration_months'] = $validated['estimated_duration_months'] ?? 0;
        $validated['engineers_required'] = $validated['engineers_required'] ?? 0;
        $validated['technicians_required'] = $validated['technicians_required'] ?? 0;
        $validated['laborers_required'] = $validated['laborers_required'] ?? 0;
        $validated['labor_cost'] = $validated['labor_cost'] ?? 0;
        $validated['material_cost'] = $validated['material_cost'] ?? 0;
        $validated['equipment_rental_cost'] = $validated['equipment_rental_cost'] ?? 0;
        $validated['other_expenses'] = $validated['other_expenses'] ?? 0;

        $project->update($validated);

        return redirect()->route('project-planning.index')
            ->with('success', 'Project planning request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = ProjectPlanning::findOrFail($id);
        $project->delete();

        return redirect()->route('project-planning.index')
            ->with('success', 'Project planning request deleted successfully.');
    }
}
