<?php

namespace App\Http\Controllers\DocumentTracking;

use App\Http\Controllers\Controller;
use App\Models\UploadedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadedDocumentController extends Controller
{
    /**
     * Display the upload form and list of uploaded documents.
     */
    public function index(Request $request)
    {
        $query = UploadedDocument::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhere('document_title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('uploaded_by', 'like', "%{$search}%");
            });
        }

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        // Order by latest first
        $query->orderBy('upload_date', 'desc');

        // Get all documents for the table
        $documents = $query->paginate(10);

        // Get recent uploads for sidebar
        $recentUploads = UploadedDocument::orderBy('upload_date', 'desc')->take(5)->get();

        return view('admin.documenttracking.upload-document-tracking', compact('documents', 'recentUploads'));
    }

    /**
     * Show the form for creating a new uploaded document.
     */
    public function create()
    {
        return view('admin.documenttracking.upload-document-tracking');
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_title' => 'required|string|max:255',
            'description' => 'required|string',
            'document_type' => 'required|string',
            'category' => 'required|string',
            'files.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240', // 10MB max
            'reference_number' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:effective_date',
            'visibility' => 'required|string|in:Public,Internal,Restricted,Confidential',
            'authorized_users' => 'nullable|string',
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('documents', $fileName, 'public');

                    UploadedDocument::create([
                        'document_title' => $request->document_title,
                        'description' => $request->description,
                        'document_type' => $request->document_type,
                        'category' => $request->category,
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getMimeType(),
                        'reference_number' => $request->reference_number,
                        'tags' => $request->tags,
                        'effective_date' => $request->effective_date,
                        'expiration_date' => $request->expiration_date,
                        'visibility' => $request->visibility,
                        'view_only_access' => $request->has('view_only_access'),
                        'download_permission' => $request->has('download_permission'),
                        'edit_permission' => $request->has('edit_permission'),
                        'share_permission' => $request->has('share_permission'),
                        'authorized_users' => $request->authorized_users ? explode(',', $request->authorized_users) : null,
                        'uploaded_by' => auth()->user()->name ?? 'Admin User',
                        'department' => auth()->user()->department ?? 'Operations',
                        'status' => 'Active',
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Document(s) uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error uploading document: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified uploaded document.
     */
    public function show(UploadedDocument $uploadedDocument)
    {
        return view('admin.documenttracking.uploaded-document-show', compact('uploadedDocument'));
    }

    /**
     * Show the form for editing the specified uploaded document.
     */
    public function edit(UploadedDocument $uploadedDocument)
    {
        return view('admin.documenttracking.uploaded-document-edit', compact('uploadedDocument'));
    }

    /**
     * Update the specified uploaded document.
     */
    public function update(Request $request, UploadedDocument $uploadedDocument)
    {
        $request->validate([
            'document_title' => 'required|string|max:255',
            'description' => 'required|string',
            'document_type' => 'required|string',
            'category' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:effective_date',
            'visibility' => 'required|string|in:Public,Internal,Restricted,Confidential',
            'authorized_users' => 'nullable|string',
        ]);

        $uploadedDocument->update([
            'document_title' => $request->document_title,
            'description' => $request->description,
            'document_type' => $request->document_type,
            'category' => $request->category,
            'reference_number' => $request->reference_number,
            'tags' => $request->tags,
            'effective_date' => $request->effective_date,
            'expiration_date' => $request->expiration_date,
            'visibility' => $request->visibility,
            'view_only_access' => $request->has('view_only_access'),
            'download_permission' => $request->has('download_permission'),
            'edit_permission' => $request->has('edit_permission'),
            'share_permission' => $request->has('share_permission'),
            'authorized_users' => $request->authorized_users ? explode(',', $request->authorized_users) : null,
            'status' => $request->status ?? 'Active',
        ]);

        return redirect()->route('uploaded-documents.index')->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified uploaded document.
     */
    public function destroy(UploadedDocument $uploadedDocument)
    {
        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($uploadedDocument->file_path)) {
                Storage::disk('public')->delete($uploadedDocument->file_path);
            }

            // Delete database record
            $uploadedDocument->delete();

            return redirect()->back()->with('success', 'Document deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting document: ' . $e->getMessage());
        }
    }

    /**
     * Download the specified document.
     */
    public function download(UploadedDocument $uploadedDocument)
    {
        if (!$uploadedDocument->canBeDownloaded()) {
            return abort(403, 'You do not have permission to download this document.');
        }

        try {
            return Storage::disk('public')->download($uploadedDocument->file_path, $uploadedDocument->file_name);
        } catch (\Exception $e) {
            return abort(404, 'File not found.');
        }
    }
}
