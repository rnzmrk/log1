<?php

namespace App\Http\Controllers\DocumentTracking;

use App\Http\Controllers\Controller;
use App\Models\DocumentReport;
use App\Models\UploadedDocument;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;

class DocumentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DocumentReport::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('report_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('report_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('generated_by', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by report type
        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get statistics for dashboard
        $stats = $this->getStatistics();

        return view('admin.documenttracking.reports', compact('reports', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.documenttracking.reports-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|in:Summary,Storage,Access,Compliance,Upload,Document Requests',
            'generated_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'report_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'parameters' => 'nullable|string',
        ]);

        $validated['status'] = 'Processing';

        DocumentReport::create($validated);

        return redirect()->route('document-reports.index')
            ->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = DocumentReport::findOrFail($id);
        return view('admin.documenttracking.reports-show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $report = DocumentReport::findOrFail($id);
        return view('admin.documenttracking.reports-edit', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $report = DocumentReport::findOrFail($id);

        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|in:Summary,Storage,Access,Compliance,Upload,Document Requests',
            'status' => 'required|in:Completed,Processing,Scheduled,Failed,Cancelled',
            'generated_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'report_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'parameters' => 'nullable|string',
        ]);

        $report->update($validated);

        return redirect()->route('document-reports.index')
            ->with('success', 'Report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $report = DocumentReport::findOrFail($id);
        $report->delete();

        return redirect()->route('document-reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    /**
     * Get statistics for the dashboard
     */
    private function getStatistics()
    {
        // Get real data from existing tables
        $totalDocuments = UploadedDocument::count();
        $pendingRequests = DocumentRequest::where('status', 'Pending')->count();
        $processedToday = UploadedDocument::whereDate('created_at', today())->count();
        
        // Calculate storage usage
        $totalFileSize = UploadedDocument::sum('file_size');
        $storageUsedGB = $totalFileSize / 1024 / 1024 / 1024;
        $storageTotalGB = 50;
        $storagePercentage = ($storageUsedGB / $storageTotalGB) * 100;

        // Get monthly trends (last 6 months)
        $documentTrend = $this->getMonthlyTrend('UploadedDocument', 6);
        $requestTrend = $this->getMonthlyTrend('DocumentRequest', 6);

        return [
            'total_documents' => $totalDocuments,
            'pending_requests' => $pendingRequests,
            'processed_today' => $processedToday,
            'storage_used' => round($storageUsedGB, 1),
            'storage_percentage' => round($storagePercentage, 0),
            'storage_total' => $storageTotalGB,
            'document_trend' => $documentTrend,
            'request_trend' => $requestTrend,
            'document_requests' => $this->getDocumentRequestsStats(),
            'storage_usage' => $this->getStorageUsageStats(),
        ];
    }

    /**
     * Get monthly trend data
     */
    private function getMonthlyTrend($model, $months)
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = app("App\\Models\\{$model}")->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $data[] = $count;
        }
        return $data;
    }

    /**
     * Get document requests statistics
     */
    private function getDocumentRequestsStats()
    {
        $thisMonth = DocumentRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $completed = DocumentRequest::where('status', 'Completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $pending = DocumentRequest::where('status', 'Pending')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'this_month' => $thisMonth,
            'completed' => $completed,
            'pending' => $pending,
        ];
    }

    /**
     * Get storage usage statistics
     */
    private function getStorageUsageStats()
    {
        $contracts = UploadedDocument::where('document_type', 'Contract')->sum('file_size');
        $reports = UploadedDocument::where('document_type', 'Report')->sum('file_size');
        $others = UploadedDocument::whereNotIn('document_type', ['Contract', 'Report'])->sum('file_size');

        return [
            'contracts' => round($contracts / 1024 / 1024 / 1024, 1),
            'reports' => round($reports / 1024 / 1024 / 1024, 1),
            'others' => round($others / 1024 / 1024 / 1024, 1),
        ];
    }
}
