<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
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

        // Apply filters and paginate
        $query = Vendor::query();
        $query->filter($filters);
        $vendors = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_vendors' => Vendor::count(),
            'active_vendors' => Vendor::where('status', 'Active')->count(),
            'pending_vendors' => Vendor::where('status', 'Pending')->count(),
            'high_rated_vendors' => Vendor::where('rating', '>=', 4.0)->count(),
        ];

        // Get filter options
        $filterOptions = [
            'categories' => Vendor::distinct('category')->pluck('category')->filter()->sort()->values(),
        ];

        return view('admin.procurement.vendors', compact('vendors', 'stats', 'filterOptions'));
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
            'status' => 'required|in:Active,Inactive,Pending,Suspended,Under Review',
            'rating' => 'nullable|decimal:2|min:0|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = auth()->id();

        Vendor::create($validated);

        return redirect()->route('procurement.vendors')
            ->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor)
    {
        return view('admin.procurement.vendors-show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.procurement.vendors-edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
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
            'status' => 'required|in:Active,Inactive,Pending,Suspended,Under Review',
            'rating' => 'nullable|decimal:2|min:0|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        $vendor->update($validated);

        return redirect()->route('procurement.vendors')
            ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('procurement.vendors')
            ->with('success', 'Vendor deleted successfully.');
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
        $query = Vendor::query();
        $query->filter($filters);
        $vendors = $query->orderBy('name')->get();

        // Generate CSV
        $filename = 'vendors-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($vendors) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Vendor Code',
                'Name',
                'Contact Person',
                'Email',
                'Phone',
                'Address',
                'City',
                'State',
                'Postal Code',
                'Country',
                'Category',
                'Services',
                'Tax ID',
                'Payment Terms',
                'Bank Name',
                'Bank Account',
                'Status',
                'Rating',
                'Notes',
                'Created At'
            ]);

            // CSV Data
            foreach ($vendors as $vendor) {
                fputcsv($file, [
                    $vendor->vendor_code,
                    $vendor->name,
                    $vendor->contact_person,
                    $vendor->email,
                    $vendor->phone,
                    $vendor->address,
                    $vendor->city,
                    $vendor->state,
                    $vendor->postal_code,
                    $vendor->country,
                    $vendor->category,
                    is_array($vendor->services) ? implode(', ', $vendor->services) : $vendor->services,
                    $vendor->tax_id,
                    $vendor->payment_terms,
                    $vendor->bank_name,
                    $vendor->bank_account,
                    $vendor->status,
                    $vendor->rating,
                    $vendor->notes,
                    $vendor->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
