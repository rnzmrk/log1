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
            'project_address' => 'required|string|max:255',
            'project_city' => 'required|string|max:255',
            'project_state' => 'required|string|max:255',
            'project_zipcode' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

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
            'project_address' => 'required|string|max:255',
            'project_city' => 'required|string|max:255',
            'project_state' => 'required|string|max:255',
            'project_zipcode' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

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

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Project planning request deleted successfully.']);
        }

        return redirect()->route('project-planning.index')
            ->with('success', 'Project planning request deleted successfully.');
    }

    /**
     * Approve the specified project.
     */
    public function approve(string $id)
    {
        $project = ProjectPlanning::findOrFail($id);
        
        // Check if project can be approved
        if (!in_array($project->status, ['Draft', 'Submitted', 'Under Review', 'In Progress'])) {
            return redirect()->route('project-planning.index')
                ->with('error', 'This project cannot be approved in its current status.');
        }

        $project->status = 'Approved';
        $project->approved_by = auth()->user()->name ?? 'System';
        $project->approved_date = now();
        $project->save();

        return redirect()->route('project-planning.index')
            ->with('success', 'Project approved successfully.');
    }

    /**
     * Reject the specified project.
     */
    public function reject(string $id)
    {
        $project = ProjectPlanning::findOrFail($id);
        
        // Check if project can be rejected
        if (!in_array($project->status, ['Draft', 'Submitted', 'Under Review', 'In Progress', 'Approved'])) {
            return redirect()->route('project-planning.index')
                ->with('error', 'This project cannot be rejected in its current status.');
        }

        $project->status = 'Cancelled';
        $project->save();

        return redirect()->route('project-planning.index')
            ->with('success', 'Project rejected successfully.');
    }
}
