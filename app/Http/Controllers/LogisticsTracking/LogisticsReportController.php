<?php

namespace App\Http\Controllers\LogisticsTracking;

use App\Http\Controllers\Controller;
use App\Models\LogisticsReport;
use App\Models\DeliveryConfirmation;
use App\Models\ProjectPlanning;
use Illuminate\Http\Request;

class LogisticsReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LogisticsReport::query();

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

        return view('admin.logistictracking.reports', compact('reports', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.logistictracking.reports-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|in:Delivery,Vehicle,Project,Performance,Financial,Inventory,Maintenance',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'generated_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'report_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'Processing';
        $validated['total_records'] = 0;
        $validated['success_rate'] = 0;

        LogisticsReport::create($validated);

        return redirect()->route('logistics-reports.index')
            ->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = LogisticsReport::findOrFail($id);
        return view('admin.logistictracking.reports-show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $report = LogisticsReport::findOrFail($id);
        return view('admin.logistictracking.reports-edit', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $report = LogisticsReport::findOrFail($id);

        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|in:Delivery,Vehicle,Project,Performance,Financial,Inventory,Maintenance',
            'status' => 'required|in:Completed,Processing,Scheduled,Failed,Cancelled',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'generated_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'report_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'total_records' => 'nullable|integer|min:0',
            'success_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $report->update($validated);

        return redirect()->route('logistics-reports.index')
            ->with('success', 'Report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $report = LogisticsReport::findOrFail($id);
        $report->delete();

        return redirect()->route('logistics-reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    /**
     * Get statistics for the dashboard
     */
    private function getStatistics()
    {
        // Get real data from existing tables
        $totalDeliveries = DeliveryConfirmation::count();
        $activeProjects = ProjectPlanning::where('status', 'Active')->orWhere('status', 'In Progress')->count();
        $totalReports = LogisticsReport::count();
        $completedReports = LogisticsReport::where('status', 'Completed')->count();

        // Calculate success rate
        $successRate = $totalReports > 0 ? ($completedReports / $totalReports) * 100 : 0;

        // Get monthly trends (last 6 months)
        $deliveryTrend = $this->getMonthlyTrend('DeliveryConfirmation', 6);
        $projectTrend = $this->getMonthlyTrend('ProjectPlanning', 6);

        return [
            'total_deliveries' => $totalDeliveries,
            'vehicle_requests' => 389, // This would come from a vehicle requests table when available
            'active_projects' => $activeProjects,
            'success_rate' => round($successRate, 1),
            'delivery_trend' => $deliveryTrend,
            'project_trend' => $projectTrend,
            'delivery_performance' => $this->getDeliveryPerformance(),
            'vehicle_utilization' => $this->getVehicleUtilization(),
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
     * Get delivery performance metrics
     */
    private function getDeliveryPerformance()
    {
        $total = DeliveryConfirmation::count();
        $onTime = DeliveryConfirmation::where('status', 'Delivered')->count();
        $delayed = DeliveryConfirmation::where('status', 'Pending')->count();
        $failed = DeliveryConfirmation::where('status', 'Cancelled')->count();

        return [
            'on_time' => $onTime,
            'delayed' => $delayed,
            'failed' => $failed,
            'total' => $total,
        ];
    }

    /**
     * Get vehicle utilization metrics
     */
    private function getVehicleUtilization()
    {
        // Mock data for now - would come from vehicle management system
        return [
            'in_use' => 76,
            'available' => 18,
            'maintenance' => 6,
        ];
    }
}
