<?php

namespace App\Http\Controllers\DocumentTracking;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of all document requests for the list view.
     */
    public function listRequests(Request $request)
    {
        $query = DocumentRequest::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('request_title', 'like', "%{$search}%")
                  ->orWhere('requested_by', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('request_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('request_date', '<=', $request->to_date);
        }

        // Order by latest first
        $query->orderBy('request_date', 'desc');

        // Paginate results
        $requests = $query->paginate(10);

        return view('admin.documenttracking.list-document-request', compact('requests'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DocumentRequest::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('request_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('request_title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('requested_by', 'like', '%' . $searchTerm . '%')
                  ->orWhere('contact_person', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.documenttracking.document-request', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.documenttracking.document-request-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'urgency' => 'required|in:Normal (3-5 days),Urgent (1-2 days),Critical (Same day)',
            'document_type' => 'required|in:Contract,Invoice,Receipt,Report,Certificate,License,Permit,Identification,Other',
            'document_category' => 'required|in:Legal,Financial,HR,Operations,Compliance,Technical,Administrative',
            'format_required' => 'required|string|max:255',
            'number_of_copies' => 'nullable|integer|min:1',
            'date_range' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notarization_required' => 'nullable|boolean',
            'apostille_needed' => 'nullable|boolean',
            'translation_required' => 'nullable|boolean',
            'certified_true_copy' => 'nullable|boolean',
            'delivery_method' => 'required|in:Digital Download,Email Attachment,Office Pickup,Courier Delivery,Registered Mail',
            'delivery_address' => 'nullable|string',
            'contact_person' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'purpose' => 'required|string',
            'project_name' => 'nullable|string|max:255',
            'cost_center' => 'nullable|string|max:255',
            'requested_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'Draft';
        $validated['notarization_required'] = $request->has('notarization_required');
        $validated['apostille_needed'] = $request->has('apostille_needed');
        $validated['translation_required'] = $request->has('translation_required');
        $validated['certified_true_copy'] = $request->has('certified_true_copy');

        DocumentRequest::create($validated);

        return redirect()->route('document-requests.index')
            ->with('success', 'Document request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $request = DocumentRequest::findOrFail($id);
        return view('admin.documenttracking.document-request-show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $request = DocumentRequest::findOrFail($id);
        return view('admin.documenttracking.document-request-edit', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $documentRequest = DocumentRequest::findOrFail($id);

        $validated = $request->validate([
            'request_title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'urgency' => 'required|in:Normal (3-5 days),Urgent (1-2 days),Critical (Same day)',
            'document_type' => 'required|in:Contract,Invoice,Receipt,Report,Certificate,License,Permit,Identification,Other',
            'document_category' => 'required|in:Legal,Financial,HR,Operations,Compliance,Technical,Administrative',
            'format_required' => 'required|string|max:255',
            'number_of_copies' => 'nullable|integer|min:1',
            'date_range' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notarization_required' => 'nullable|boolean',
            'apostille_needed' => 'nullable|boolean',
            'translation_required' => 'nullable|boolean',
            'certified_true_copy' => 'nullable|boolean',
            'delivery_method' => 'required|in:Digital Download,Email Attachment,Office Pickup,Courier Delivery,Registered Mail',
            'delivery_address' => 'nullable|string',
            'contact_person' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'purpose' => 'required|string',
            'project_name' => 'nullable|string|max:255',
            'cost_center' => 'nullable|string|max:255',
            'status' => 'required|in:Draft,Submitted,Under Review,Approved,Processing,Completed,Rejected,Cancelled',
            'requested_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['notarization_required'] = $request->has('notarization_required');
        $validated['apostille_needed'] = $request->has('apostille_needed');
        $validated['translation_required'] = $request->has('translation_required');
        $validated['certified_true_copy'] = $request->has('certified_true_copy');

        $documentRequest->update($validated);

        return redirect()->route('document-requests.index')
            ->with('success', 'Document request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $documentRequest = DocumentRequest::findOrFail($id);
        $documentRequest->delete();

        return redirect()->route('document-requests.index')
            ->with('success', 'Document request deleted successfully.');
    }
}
