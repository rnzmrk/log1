<?php

namespace App\Http\Controllers\DocumentTracking;

use App\Http\Controllers\Controller;
use App\Models\UploadedDocument;
use App\Models\Contract;
use App\Models\Supplier;
use App\Models\SupplierVerification;
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

        // Get contracts for the contracts section
        $contracts = Contract::orderBy('created_at', 'desc')->paginate(10);

        // Get statistics for contracts
        $stats = [
            'total' => Contract::count(),
            'active' => Contract::where('status', 'Active')->count(),
            'expiring_soon' => Contract::where('end_date', '<=', now()->addDays(30))->where('end_date', '>', now())->count(),
            'total_value' => Contract::sum('contract_value'),
            'active_value' => Contract::where('status', 'Active')->sum('contract_value'),
        ];

        // Get contracts needing attention
        $expiringSoon = Contract::where('end_date', '<=', now()->addDays(30))->where('end_date', '>', now())->get();
        $needingRenewal = Contract::where('end_date', '<=', now()->addDays(60))->where('status', 'Active')->get();

        return view('admin.documenttracking.upload-document-tracking', compact('documents', 'recentUploads', 'contracts', 'stats', 'expiringSoon', 'needingRenewal'));
    }

    /**
     * Show the create contract page with contracts table.
     */
    public function createContract()
    {
        // Get contracts for the contracts section
        $contracts = Contract::orderBy('created_at', 'desc')->paginate(10);

        // Get statistics for contracts
        $stats = [
            'total' => Contract::count(),
            'active' => Contract::where('status', 'Active')->count(),
            'expiring_soon' => Contract::where('end_date', '<=', now()->addDays(30))->where('end_date', '>', now())->count(),
            'total_value' => Contract::sum('contract_value'),
            'active_value' => Contract::where('status', 'Active')->sum('contract_value'),
        ];

        // Get contracts needing attention
        $expiringSoon = Contract::where('end_date', '<=', now()->addDays(30))->where('end_date', '>', now())->get();
        $needingRenewal = Contract::where('end_date', '<=', now()->addDays(60))->where('status', 'Active')->get();

        return view('admin.documenttracking.create-contract', compact('contracts', 'stats', 'expiringSoon', 'needingRenewal'));
    }

    /**
     * Show the form for creating a new uploaded document.
     */
    public function create()
    {
        return view('admin.documenttracking.upload-document-tracking');
    }

    /**
     * Show the create document and reports page with contracts table.
     */
    public function createDocumentReports(Request $request)
    {
        // Get recent contracts with filtering
        $query = Contract::orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('contract_name', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Contract type filter
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        // Vendor filter
        if ($request->filled('vendor')) {
            $query->where('vendor', 'like', "%{$request->vendor}%");
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Renewal status filter
        if ($request->filled('renewal_status')) {
            switch ($request->renewal_status) {
                case 'expiring_soon':
                    $query->where('end_date', '<=', now()->addDays(30))
                          ->where('end_date', '>', now());
                    break;
                case 'needs_renewal':
                    $query->where('end_date', '<=', now()->addDays(60))
                          ->where('status', 'Active');
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
            }
        }

        $recentContracts = $query->paginate(10);

        // Get statistics for contracts and documents
        $stats = [
            'total_contracts' => Contract::count(),
            'active_contracts' => Contract::where('status', 'Active')->count(),
            'pending_contracts' => Contract::where('status', 'Pending')->count(),
            'total_documents' => UploadedDocument::count(),
        ];

        // Get contracts needing attention
        $expiringSoon = Contract::where('end_date', '<=', now()->addDays(30))->where('end_date', '>', now())->get();
        $needingRenewal = Contract::where('end_date', '<=', now()->addDays(60))->where('status', 'Active')->get();

        return view('admin.documenttracking.create-document-reports', compact('recentContracts', 'stats', 'expiringSoon', 'needingRenewal'));
    }

    /**
     * Show contract details.
     */
    public function showContract($id)
    {
        $contract = Contract::findOrFail($id);
        
        // Note: Related documents section commented out since contract_id column doesn't exist
        // $relatedDocuments = UploadedDocument::where('contract_id', $id)->get();
        $relatedDocuments = collect(); // Empty collection for now
        
        return view('admin.documenttracking.show-contract', compact('contract', 'relatedDocuments'));
    }

    /**
     * Export contracts to CSV.
     */
    public function exportContracts(Request $request)
    {
        // Build query with same filters as createDocumentReports
        $query = Contract::orderBy('created_at', 'desc');

        // Apply filters if they exist
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('contract_name', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        if ($request->filled('vendor')) {
            $query->where('vendor', 'like', "%{$request->vendor}%");
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('renewal_status')) {
            switch ($request->renewal_status) {
                case 'expiring_soon':
                    $query->where('end_date', '<=', now()->addDays(30))
                          ->where('end_date', '>', now());
                    break;
                case 'needs_renewal':
                    $query->where('end_date', '<=', now()->addDays(60))
                          ->where('status', 'Active');
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
            }
        }

        $contracts = $query->get();

        // Create CSV content
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvContent .= "Contract Number,Contract Name,Vendor,Contract Type,Contract Value,Start Date,End Date,Status,Priority,Created By,Created At\n";

        foreach ($contracts as $contract) {
            $csvContent .= '"' . $contract->contract_number . '",';
            $csvContent .= '"' . $contract->contract_name . '",';
            $csvContent .= '"' . $contract->vendor . '",';
            $csvContent .= '"' . $contract->contract_type . '",';
            $csvContent .= '"' . ($contract->contract_value ? number_format($contract->contract_value, 2) : '') . '",';
            $csvContent .= '"' . $contract->start_date->format('Y-m-d') . '",';
            $csvContent .= '"' . $contract->end_date->format('Y-m-d') . '",';
            $csvContent .= '"' . $contract->status . '",';
            $csvContent .= '"' . $contract->priority . '",';
            $csvContent .= '"' . ($contract->created_by ?? '') . '",';
            $csvContent .= '"' . $contract->created_at->format('Y-m-d H:i:s') . '"';
            $csvContent .= "\n";
        }

        // Generate filename with timestamp
        $filename = 'contracts_export_' . date('Y-m-d_H-i-s') . '.csv';

        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Show supplier validation page.
     */
    public function validation(Request $request)
    {
        // Get suppliers with filtering
        $query = Supplier::orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Validation status filter (using supplier status)
        if ($request->filled('validation_status')) {
            $query->where('status', $request->validation_status);
        }

        // Date range filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $suppliers = $query->paginate(10);

        // Get statistics
        $stats = [
            'total_suppliers' => Supplier::count(),
            'validated' => Supplier::where('status', 'Active')->count(),
            'pending' => Supplier::where('status', 'Pending')->count(),
            'rejected' => Supplier::where('status', 'Suspended')->count(),
        ];

        return view('admin.documenttracking.validation', compact('suppliers', 'stats'));
    }

    /**
     * Show supplier validation details.
     */
    public function showValidation($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.documenttracking.show-validation', compact('supplier'));
    }

    /**
     * Export supplier validation data to CSV.
     */
    public function exportValidation(Request $request)
    {
        // Build query with same filters as validation method
        $query = Supplier::orderBy('created_at', 'desc');

        // Apply filters if they exist
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Validation status filter (using supplier status)
        if ($request->filled('validation_status')) {
            $query->where('status', $request->validation_status);
        }

        // Date range filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $suppliers = $query->get();

        // Create CSV content
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvContent .= "Supplier Name,Vendor Code,Contact Person,Category,Email,Phone,Address,City,State,Postal Code,Country,Website,Status,Created At\n";

        foreach ($suppliers as $supplier) {
            $csvContent .= '"' . $supplier->name . '",';
            $csvContent .= '"' . ($supplier->vendor_code ?? '') . '",';
            $csvContent .= '"' . $supplier->contact_person . '",';
            $csvContent .= '"' . ($supplier->category ?? '') . '",';
            $csvContent .= '"' . $supplier->email . '",';
            $csvContent .= '"' . $supplier->phone . '",';
            $csvContent .= '"' . ($supplier->address ?? '') . '",';
            $csvContent .= '"' . ($supplier->city ?? '') . '",';
            $csvContent .= '"' . ($supplier->state ?? '') . '",';
            $csvContent .= '"' . ($supplier->postal_code ?? '') . '",';
            $csvContent .= '"' . ($supplier->country ?? '') . '",';
            $csvContent .= '"' . ($supplier->website ?? '') . '",';
            $csvContent .= '"' . $supplier->status . '",';
            $csvContent .= '"' . $supplier->created_at->format('Y-m-d H:i:s') . '"';
            $csvContent .= "\n";
        }

        // Generate filename with timestamp
        $filename = 'supplier_validation_' . date('Y-m-d_H-i-s') . '.csv';

        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Show supplier verification page.
     */
    public function verification(Request $request)
    {
        // Get verifications with filtering
        $query = SupplierVerification::with(['supplier', 'verifier'])->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('supplier', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%")
                         ->orWhere('vendor_code', 'like', "%{$search}%");
                })
                ->orWhere('verification_type', 'like', "%{$search}%")
                ->orWhere('findings', 'like', "%{$search}%")
                ->orWhereHas('verifier', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Verification status filter
        if ($request->filled('verification_status')) {
            $query->where('status', $request->verification_status);
        }

        // Verification type filter
        if ($request->filled('verification_type')) {
            $query->where('verification_type', $request->verification_type);
        }

        // Score range filter
        if ($request->filled('min_score')) {
            $query->where('score', '>=', $request->min_score);
        }
        if ($request->filled('max_score')) {
            $query->where('score', '<=', $request->max_score);
        }

        // Date range filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $verifications = $query->paginate(10);

        // Get statistics
        $stats = [
            'total_verifications' => SupplierVerification::count(),
            'passed' => SupplierVerification::where('status', 'passed')->count(),
            'failed' => SupplierVerification::where('status', 'failed')->count(),
            'pending' => SupplierVerification::where('status', 'pending')->count(),
        ];

        return view('admin.documenttracking.verification', compact('verifications', 'stats'));
    }

    /**
     * Show supplier verification details.
     */
    public function showVerification($id)
    {
        $verification = SupplierVerification::with(['supplier', 'verifier', 'scheduler'])->findOrFail($id);
        return view('admin.documenttracking.show-verification', compact('verification'));
    }

    /**
     * Export supplier verification data to CSV.
     */
    public function exportVerification(Request $request)
    {
        // Build query with same filters as verification method
        $query = SupplierVerification::with(['supplier', 'verifier'])->orderBy('created_at', 'desc');

        // Apply filters if they exist
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('supplier', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%")
                         ->orWhere('vendor_code', 'like', "%{$search}%");
                })
                ->orWhere('verification_type', 'like', "%{$search}%")
                ->orWhere('findings', 'like', "%{$search}%")
                ->orWhereHas('verifier', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Verification status filter
        if ($request->filled('verification_status')) {
            $query->where('status', $request->verification_status);
        }

        // Verification type filter
        if ($request->filled('verification_type')) {
            $query->where('verification_type', $request->verification_type);
        }

        // Score range filter
        if ($request->filled('min_score')) {
            $query->where('score', '>=', $request->min_score);
        }
        if ($request->filled('max_score')) {
            $query->where('score', '<=', $request->max_score);
        }

        // Date range filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $verifications = $query->get();

        // Create CSV content
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvContent .= "Supplier Name,Verification Type,Verification Date,Status,Score,Grade,Findings,Recommendations,Verified By,Created At\n";

        foreach ($verifications as $verification) {
            $csvContent .= '"' . ($verification->supplier->name ?? 'N/A') . '",';
            $csvContent .= '"' . ucfirst($verification->verification_type) . '",';
            $csvContent .= '"' . ($verification->verification_date ? $verification->verification_date->format('Y-m-d') : '') . '",';
            $csvContent .= '"' . ucfirst($verification->status) . '",';
            $csvContent .= '"' . ($verification->score ?? '') . '",';
            $csvContent .= '"' . $verification->grade . '",';
            $csvContent .= '"' . str_replace('"', '""', $verification->findings ?? '') . '",';
            $csvContent .= '"' . str_replace('"', '""', $verification->recommendations ?? '') . '",';
            $csvContent .= '"' . ($verification->verifier->name ?? 'N/A') . '",';
            $csvContent .= '"' . $verification->created_at->format('Y-m-d H:i:s') . '"';
            $csvContent .= "\n";
        }

        // Generate filename with timestamp
        $filename = 'supplier_verification_' . date('Y-m-d_H-i-s') . '.csv';

        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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
