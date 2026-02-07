<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogisticsReport;

class LogisticsReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [
            [
                'report_number' => 'RPT-2026-0001',
                'report_name' => 'Monthly Delivery Summary',
                'report_type' => 'Delivery',
                'status' => 'Completed',
                'priority' => 'Medium',
                'generated_by' => 'John Anderson',
                'department' => 'Logistics',
                'report_date' => '2024-02-07',
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31',
                'description' => 'Monthly summary of all delivery operations and performance metrics',
                'total_records' => 1247,
                'success_rate' => 94.20,
                'notes' => 'Report generated successfully with all delivery data included',
            ],
            [
                'report_number' => 'RPT-2026-0002',
                'report_name' => 'Vehicle Utilization Analysis',
                'report_type' => 'Vehicle',
                'status' => 'Completed',
                'priority' => 'High',
                'generated_by' => 'Sarah Mitchell',
                'department' => 'Operations',
                'report_date' => '2024-02-06',
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31',
                'description' => 'Analysis of vehicle usage patterns and optimization recommendations',
                'total_records' => 389,
                'success_rate' => 89.50,
                'notes' => 'Vehicle data shows 76% utilization rate',
            ],
            [
                'report_number' => 'RPT-2026-0003',
                'report_name' => 'Project Progress Report',
                'report_type' => 'Project',
                'status' => 'Processing',
                'priority' => 'High',
                'generated_by' => 'Michael Chen',
                'department' => 'Project Management',
                'report_date' => '2024-02-05',
                'start_date' => '2024-01-01',
                'end_date' => '2024-02-05',
                'description' => 'Current status of all active projects and milestone achievements',
                'total_records' => 47,
                'success_rate' => 87.30,
                'notes' => 'Report is being processed with latest project updates',
            ],
            [
                'report_number' => 'RPT-2026-0004',
                'report_name' => 'Performance Metrics Dashboard',
                'report_type' => 'Performance',
                'status' => 'Completed',
                'priority' => 'Medium',
                'generated_by' => 'Emily Rodriguez',
                'department' => 'Finance',
                'report_date' => '2024-02-04',
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31',
                'description' => 'Comprehensive performance metrics across all departments',
                'total_records' => 156,
                'success_rate' => 92.10,
                'notes' => 'All KPIs tracked and analyzed successfully',
            ],
            [
                'report_number' => 'RPT-2026-0005',
                'report_name' => 'Financial Summary Report',
                'report_type' => 'Financial',
                'status' => 'Scheduled',
                'priority' => 'Urgent',
                'generated_by' => 'David Kim',
                'department' => 'Finance',
                'report_date' => '2024-02-03',
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31',
                'description' => 'Monthly financial summary with cost analysis and budget tracking',
                'total_records' => 0,
                'success_rate' => 0,
                'notes' => 'Report scheduled for generation on Feb 10, 2024',
            ],
            [
                'report_number' => 'RPT-2026-0006',
                'report_name' => 'Inventory Status Report',
                'report_type' => 'Inventory',
                'status' => 'Processing',
                'priority' => 'Medium',
                'generated_by' => 'Lisa Wang',
                'department' => 'Warehouse',
                'report_date' => '2024-02-02',
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31',
                'description' => 'Current inventory levels and stock movement analysis',
                'total_records' => 2341,
                'success_rate' => 78.90,
                'notes' => 'Processing large dataset, expected completion in 2 hours',
            ],
            [
                'report_number' => 'RPT-2026-0007',
                'report_name' => 'Equipment Maintenance Schedule',
                'report_type' => 'Maintenance',
                'status' => 'Completed',
                'priority' => 'Low',
                'generated_by' => 'Tom Harris',
                'department' => 'Maintenance',
                'report_date' => '2024-02-01',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'description' => 'Annual equipment maintenance schedule and compliance report',
                'total_records' => 89,
                'success_rate' => 96.70,
                'notes' => 'All maintenance schedules updated and approved',
            ],
        ];

        foreach ($reports as $report) {
            LogisticsReport::create($report);
        }

        $this->command->info('Sample logistics reports created successfully!');
    }
}
