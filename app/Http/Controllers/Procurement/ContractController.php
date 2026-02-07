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

        $contracts = $query->with('supplier')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.procurement.create-contract-reports', compact('contracts'));
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
        $contract = Contract::findOrFail($id);
        return view('admin.procurement.create-contract-reports-show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contract = Contract::findOrFail($id);
        return view('admin.procurement.create-contract-reports-edit', compact('contract'));
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
}
