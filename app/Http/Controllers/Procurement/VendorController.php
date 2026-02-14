<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        // Build filters from request
        $filters = [
            'search' => $request->search,
            'status' => $request->status,
            'category' => $request->category,
            'rating' => $request->rating,
        ];

        // Apply filters and paginate - show all suppliers (including website registrations)
        $query = Supplier::query();
        $query->filter($filters);
        $suppliers = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_suppliers' => Supplier::count(),
            'active_suppliers' => Supplier::whereIn('status', ['Accepted', 'Active'])->count(),
            'pending_suppliers' => Supplier::where('status', 'Pending')->count(),
            'high_rated_suppliers' => Supplier::where('rating', '>=', 4.0)->count(),
        ];

        // Get filter options
        $filterOptions = [
            'categories' => Supplier::distinct('category')->pluck('category')->filter()->sort()->values(),
        ];

        return view('admin.procurement.vendors', compact('suppliers', 'stats', 'filterOptions'));
    }

    public function create()
    {
        return view('admin.procurement.vendors-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'services' => 'nullable|array',
            'tax_id' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'status' => 'required|in:Accepted,Active,Inactive,Pending,Suspended,Under Review',
            'rating' => 'nullable|decimal:2|min:0|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = auth()->id();

        Supplier::create($validated);

        return redirect()->route('procurement.vendors')
            ->with('success', 'Supplier created successfully.');
    }

    public function show($vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        return view('admin.procurement.vendors-show', compact('supplier'));
    }

    public function edit($vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        return view('admin.procurement.vendors-edit', compact('supplier'));
    }

    public function update(Request $request, $vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'services' => 'nullable|array',
            'tax_id' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'status' => 'required|in:Accepted,Active,Inactive,Pending,Suspended,Under Review',
            'rating' => 'nullable|decimal:2|min:0|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        $supplier->update($validated);

        return redirect()->route('procurement.vendors')
            ->with('success', 'Supplier updated successfully.');
    }

    public function approve($vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        $supplier->status = 'Accepted';
        $supplier->save();

        return redirect()->route('procurement.vendors')
            ->with('success', 'Supplier accepted successfully.');
    }

    public function destroy($vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        $supplier->delete();

        return redirect()->route('procurement.vendors')
            ->with('success', 'Supplier deleted successfully.');
    }

    public function export(Request $request)
    {
        // Build filters from request
        $filters = [
            'search' => $request->search,
            'status' => $request->status,
            'category' => $request->category,
            'rating' => $request->rating,
        ];

        // Apply filters and get data
        $query = Supplier::query();
        $query->filter($filters);
        $suppliers = $query->orderBy('name')->get();

        // Generate CSV content with UTF-8 BOM
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel compatibility
        
        // CSV Header
        $csvContent .= "Vendor Code,Name,Contact Person,Email,Phone,Address,City,State,Postal Code,Country,Category,Services,Tax ID,Payment Terms,Bank Name,Bank Account,Status,Rating,Notes,Created At\n";

        // CSV Data
        foreach ($suppliers as $supplier) {
            $csvContent .= '"' . ($supplier->vendor_code ?? '') . '",';
            $csvContent .= '"' . $supplier->name . '",';
            $csvContent .= '"' . ($supplier->contact_person ?? '') . '",';
            $csvContent .= '"' . ($supplier->email ?? '') . '",';
            $csvContent .= '"' . ($supplier->phone ?? '') . '",';
            $csvContent .= '"' . ($supplier->address ?? '') . '",';
            $csvContent .= '"' . ($supplier->city ?? '') . '",';
            $csvContent .= '"' . ($supplier->state ?? '') . '",';
            $csvContent .= '"' . ($supplier->postal_code ?? '') . '",';
            $csvContent .= '"' . ($supplier->country ?? '') . '",';
            $csvContent .= '"' . ($supplier->category ?? '') . '",';
            $csvContent .= '"' . (is_array($supplier->services) ? implode(', ', $supplier->services) : ($supplier->services ?? '')) . '",';
            $csvContent .= '"' . ($supplier->tax_id ?? '') . '",';
            $csvContent .= '"' . ($supplier->payment_terms ?? '') . '",';
            $csvContent .= '"' . ($supplier->bank_name ?? '') . '",';
            $csvContent .= '"' . ($supplier->bank_account ?? '') . '",';
            $csvContent .= '"' . $supplier->status . '",';
            $csvContent .= '"' . ($supplier->rating ?? '') . '",';
            $csvContent .= '"' . str_replace('"', '""', $supplier->notes ?? '') . '",';
            $csvContent .= '"' . $supplier->created_at->format('Y-m-d H:i:s') . '"';
            $csvContent .= "\n";
        }

        // Generate filename with timestamp
        $filename = 'suppliers_' . date('Y-m-d_H-i-s') . '.csv';

        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
