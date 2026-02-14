<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectPlanning;

class ProjectController extends Controller
{
    /**
     * Display a listing of project planning requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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

        // Pagination
        $perPage = $request->get('per_page', 15);
        $projects = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $projects->items(),
            'pagination' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'from' => $projects->firstItem(),
                'to' => $projects->lastItem(),
            ]
        ]);
    }

    /**
     * Store a newly created project planning request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
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
                'status' => 'nullable|in:Draft,Submitted,Under Review,Approved,In Progress,On Hold,Completed,Cancelled',
            ]);

            // Set default status if not provided
            $validated['status'] = $validated['status'] ?? 'Draft';

            // Auto-generate project number
            $validated['project_number'] = 'PRJ-' . date('Y') . '-' . str_pad(ProjectPlanning::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create project
            $project = ProjectPlanning::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Project planning request created successfully.',
                'data' => $project
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create project planning request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified project planning request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $project = ProjectPlanning::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $project
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project planning request not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve project planning request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified project planning request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $project = ProjectPlanning::findOrFail($id);

            $validated = $request->validate([
                'project_name' => 'sometimes|required|string|max:255',
                'project_description' => 'sometimes|required|string',
                'project_type' => 'sometimes|required|in:Construction,Renovation,Maintenance,Installation,Inspection,Other',
                'priority' => 'sometimes|required|in:Low,Medium,High,Critical',
                'status' => 'sometimes|required|in:Draft,Submitted,Under Review,Approved,In Progress,On Hold,Completed,Cancelled',
                'start_date' => 'sometimes|required|date',
                'end_date' => 'sometimes|required|date|after_or_equal:start_date',
                'project_address' => 'sometimes|required|string|max:255',
                'project_city' => 'sometimes|required|string|max:255',
                'project_state' => 'sometimes|required|string|max:255',
                'project_zipcode' => 'sometimes|required|string|max:255',
                'requested_by' => 'sometimes|required|string|max:255',
                'department' => 'sometimes|required|string|max:255',
                'approved_by' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            // Auto-set approved date when project is approved
            if (isset($validated['status']) && $validated['status'] === 'Approved' && !$project->approved_date) {
                $validated['approved_date'] = now();
                $validated['approved_by'] = $validated['approved_by'] ?? 'API User';
            }

            $project->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Project planning request updated successfully.',
                'data' => $project
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project planning request not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update project planning request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified project planning request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $project = ProjectPlanning::findOrFail($id);
            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project planning request deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project planning request not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project planning request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
