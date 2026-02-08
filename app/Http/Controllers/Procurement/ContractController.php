<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contract::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('contract_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('contract_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('vendor', 'like', '%' . $searchTerm . '%')
                  ->orWhere('vendor_contact', 'like', '%' . $searchTerm . '%')
                  ->orWhere('created_by', 'like', '%' . $searchTerm . '%');
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

        // Filter by contract type
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        // Filter by vendor
        if ($request->filled('vendor')) {
            $query->where('vendor', 'like', '%' . $request->vendor . '%');
        }

        // Filter by renewal status
        if ($request->filled('renewal_status')) {
            switch ($request->renewal_status) {
                case 'expiring_soon':
                    $query->where('status', 'Active')
                          ->where('end_date', '<=', now()->addDays(30))
                          ->where('end_date', '>', now());
                    break;
                case 'needs_renewal':
                    $query->where('status', 'Active')
                          ->where('end_date', '<', now());
                    break;
                case 'expired':
                    $query->where('status', 'Expired')
                          ->orWhere(function($q) {
                              $q->where('status', 'Active')
                                ->where('end_date', '<', now());
                          });
                    break;
            }
        }

        $contracts = $query->with('supplier')->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $stats = [
            'total' => Contract::count(),
            'active' => Contract::where('status', 'Active')->count(),
            'expired' => Contract::getExpired()->count(),
            'expiring_soon' => Contract::getExpiringSoon(30)->count(),
            'needs_renewal' => Contract::getNeedingRenewal()->count(),
            'total_value' => Contract::sum('contract_value'),
            'active_value' => Contract::where('status', 'Active')->sum('contract_value'),
        ];
        
        // Get contracts needing attention
        $expiringSoon = Contract::getExpiringSoon(30)->limit(5)->get();
        $needingRenewal = Contract::getNeedingRenewal()->limit(5)->get();
        
        return view('admin.procurement.create-contract-reports', compact(
            'contracts', 'stats', 'expiringSoon', 'needingRenewal'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $approvedSuppliers = Supplier::where('status', 'Active')->orderBy('name')->get();
        return view('admin.procurement.create-contract-reports-create', compact('approvedSuppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_name' => 'required|string|max:255',
            'vendor' => 'required|string|max:255',
            'vendor_contact' => 'nullable|string|max:255',
            'vendor_email' => 'nullable|email|max:255',
            'vendor_phone' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'contract_type' => 'required|in:Service,Supply,Maintenance,Consulting,Software License,Hardware Lease,Other',
            'contract_value' => 'required|numeric|min:0|max:999999999999.99',
            'status' => 'required|in:Draft,Under Review,Active,Expired,Terminated,Renewed',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'renewal_date' => 'nullable|date|after:end_date',
            'auto_renewal' => 'nullable|boolean',
            'renewal_terms' => 'nullable|string',
            'description' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'created_by' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        // Auto-generate contract number
        $validated['contract_number'] = 'CTR-' . date('Y') . '-' . str_pad(Contract::count() + 1, 4, '0', STR_PAD_LEFT);

        Contract::create($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contract = Contract::with('supplier')->findOrFail($id);
        return view('admin.procurement.create-contract-reports-show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contract = Contract::findOrFail($id);
        $approvedSuppliers = Supplier::where('status', 'Active')->orderBy('name')->get();
        return view('admin.procurement.create-contract-reports-edit', compact('contract', 'approvedSuppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contract = Contract::findOrFail($id);

        $validated = $request->validate([
            'contract_number' => 'required|string|unique:contracts,contract_number,'.$id,
            'contract_name' => 'required|string|max:255',
            'vendor' => 'required|string|max:255',
            'vendor_contact' => 'nullable|string|max:255',
            'vendor_email' => 'nullable|email|max:255',
            'vendor_phone' => 'nullable|string|max:255',
            'contract_type' => 'required|in:Service,Supply,Maintenance,Consulting,Software License,Hardware Lease,Other',
            'contract_value' => 'required|numeric|min:0|max:999999999999.99',
            'status' => 'required|in:Draft,Under Review,Active,Expired,Terminated,Renewed',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'renewal_date' => 'nullable|date|after:end_date',
            'auto_renewal' => 'nullable|boolean',
            'renewal_terms' => 'nullable|string',
            'description' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'created_by' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        $contract->update($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Contract updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Contract deleted successfully.');
    }

    /**
     * Renew a contract
     */
    public function renew(Request $request, string $id)
    {
        $contract = Contract::findOrFail($id);

        $request->validate([
            'new_end_date' => 'required|date|after:today',
            'renewal_terms' => 'nullable|string',
        ]);

        $newContract = $contract->renew(
            $request->new_end_date,
            $request->renewal_terms
        );

        // Check if request is AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contract renewed successfully. New contract: ' . $newContract->contract_number,
                'new_contract_id' => $newContract->id
            ]);
        }

        return redirect()->route('contracts.index')
            ->with('success', 'Contract renewed successfully. New contract: ' . $newContract->contract_number);
    }

    /**
     * Terminate a contract
     */
    public function terminate(Request $request, string $id)
    {
        $contract = Contract::findOrFail($id);

        $request->validate([
            'termination_reason' => 'required|string',
        ]);

        $contract->terminate($request->termination_reason);

        // Check if request is AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contract terminated successfully.'
            ]);
        }

        return redirect()->route('contracts.index')
            ->with('success', 'Contract terminated successfully.');
    }

    /**
     * Export contracts to CSV
     */
    public function export(Request $request)
    {
        $query = Contract::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('contract_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('contract_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('vendor', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        if ($request->filled('vendor')) {
            $query->where('vendor', 'like', '%' . $request->vendor . '%');
        }

        $contracts = $query->with('supplier')->orderBy('created_at', 'desc')->get();

        // Create CSV export
        $filename = 'contracts-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($contracts) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Contract Number',
                'Contract Name',
                'Vendor',
                'Vendor Contact',
                'Vendor Email',
                'Contract Type',
                'Contract Value',
                'Status',
                'Priority',
                'Start Date',
                'End Date',
                'Renewal Date',
                'Renewal Count',
                'Auto Renewal',
                'Days Until Expiration',
                'Renewal Status',
                'Duration (Days)',
                'Value Per Day',
                'Created By',
                'Approved By',
                'Created At'
            ]);

            // CSV Data
            foreach ($contracts as $contract) {
                fputcsv($file, [
                    $contract->contract_number,
                    $contract->contract_name,
                    $contract->vendor,
                    $contract->vendor_contact,
                    $contract->vendor_email,
                    $contract->contract_type,
                    $contract->contract_value,
                    $contract->status,
                    $contract->priority,
                    $contract->start_date->format('Y-m-d'),
                    $contract->end_date->format('Y-m-d'),
                    $contract->renewal_date ? $contract->renewal_date->format('Y-m-d') : '',
                    $contract->renewal_count ?? 0,
                    $contract->auto_renewal ? 'Yes' : 'No',
                    $contract->days_until_expiration,
                    $contract->renewal_status,
                    $contract->duration,
                    $contract->value_per_day,
                    $contract->created_by,
                    $contract->approved_by,
                    $contract->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk actions on contracts
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:renew,terminate,update_status,delete',
            'contract_ids' => 'required|array',
            'contract_ids.*' => 'exists:contracts,id',
            'new_end_date' => 'required_if:action,renew|date|after:today',
            'termination_reason' => 'required_if:action,terminate|string',
            'status' => 'required_if:action,update_status|in:Draft,Under Review,Active,Expired,Terminated,Renewed',
        ]);

        $contractIds = $request->contract_ids;

        if ($request->action === 'renew') {
            $count = 0;
            foreach ($contractIds as $id) {
                $contract = Contract::find($id);
                if ($contract && $contract->status === 'Active') {
                    $contract->renew($request->new_end_date);
                    $count++;
                }
            }
            return redirect()->back()->with('success', $count . ' contracts renewed successfully.');
        }

        if ($request->action === 'terminate') {
            $count = 0;
            foreach ($contractIds as $id) {
                $contract = Contract::find($id);
                if ($contract && in_array($contract->status, ['Active', 'Under Review'])) {
                    $contract->terminate($request->termination_reason);
                    $count++;
                }
            }
            return redirect()->back()->with('success', $count . ' contracts terminated successfully.');
        }

        if ($request->action === 'update_status') {
            Contract::whereIn('id', $contractIds)->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Contract status updated successfully.');
        }

        if ($request->action === 'delete') {
            Contract::whereIn('id', $contractIds)->delete();
            return redirect()->back()->with('success', 'Contracts deleted successfully.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    /**
     * Get contract statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => Contract::count(),
            'active' => Contract::where('status', 'Active')->count(),
            'expired' => Contract::getExpired()->count(),
            'expiring_soon' => Contract::getExpiringSoon(30)->count(),
            'needs_renewal' => Contract::getNeedingRenewal()->count(),
            'total_value' => Contract::sum('contract_value'),
            'active_value' => Contract::where('status', 'Active')->sum('contract_value'),
            'by_type' => Contract::selectRaw('contract_type, COUNT(*) as count, SUM(contract_value) as total_value')
                ->groupBy('contract_type')
                ->get(),
            'by_status' => Contract::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Get contracts expiring soon
     */
    public function getExpiringSoon()
    {
        $contracts = Contract::getExpiringSoon(30)->get();
        
        return response()->json($contracts);
    }

    /**
     * Get contracts needing renewal
     */
    public function getNeedingRenewal()
    {
        $contracts = Contract::getNeedingRenewal()->get();
        
        return response()->json($contracts);
    }
}
