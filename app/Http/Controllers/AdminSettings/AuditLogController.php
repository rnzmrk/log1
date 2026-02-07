<?php

namespace App\Http\Controllers\AdminSettings;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // Build filters from request
        $filters = [
            'search' => $request->search,
            'action' => $request->action,
            'module' => $request->module,
            'user' => $request->user,
            'status' => $request->status,
            'date_range' => $request->date_range,
        ];

        // Apply filters and paginate
        $query = AuditLog::query();
        $query->filter($filters);
        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $stats = [
            'total_logs' => AuditLog::count(),
            'today_activity' => AuditLog::where('created_at', '>=', now()->subHours(24))->count(),
            'failed_logins' => AuditLog::where('action', 'Failed Login')->where('status', 'Failed')->count(),
            'active_users_today' => AuditLog::where('created_at', '>=', now()->subHours(24))
                ->where('status', 'Success')
                ->distinct('user_email')
                ->count(),
        ];

        // Get filter options
        $filterOptions = [
            'users' => AuditLog::distinct('user_email')->pluck('user_email')->filter()->values(),
            'actions' => AuditLog::distinct('action')->pluck('action')->sort()->values(),
            'modules' => AuditLog::distinct('module')->pluck('module')->sort()->values(),
        ];

        return view('admin.adminsettings.audit-logs', compact('auditLogs', 'stats', 'filterOptions'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('admin.adminsettings.audit-logs-show', compact('auditLog'));
    }

    public function export(Request $request)
    {
        // Build filters from request
        $filters = [
            'search' => $request->search,
            'action' => $request->action,
            'module' => $request->module,
            'user' => $request->user,
            'status' => $request->status,
            'date_range' => $request->date_range,
        ];

        // Apply filters and get data
        $query = AuditLog::query();
        $query->filter($filters);
        $auditLogs = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'audit-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Timestamp',
                'User Name',
                'User Email',
                'Action',
                'Module',
                'IP Address',
                'Status',
                'Details',
                'User Agent'
            ]);

            // CSV Data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user_name,
                    $log->user_email,
                    $log->action,
                    $log->module,
                    $log->ip_address,
                    $log->status,
                    is_array($log->details) ? json_encode($log->details) : $log->details,
                    $log->user_agent,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function clearOldLogs(Request $request)
    {
        $days = $request->input('days', 90); // Default to 90 days
        
        $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();
        
        return redirect()->route('admin.adminsettings.audit-logs')
            ->with('success', "Deleted {$deleted} old audit logs older than {$days} days.");
    }

    // Helper method to log activities (can be called from other parts of the app)
    public static function log($action, $module, $status = 'Success', $details = null, $user = null)
    {
        $user = $user ?? auth()->user();
        
        AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Unknown User',
            'user_email' => $user?->email ?? 'unknown@example.com',
            'action' => $action,
            'module' => $module,
            'ip_address' => request()->ip(),
            'status' => $status,
            'details' => $details,
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
