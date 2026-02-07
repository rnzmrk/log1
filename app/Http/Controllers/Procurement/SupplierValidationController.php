<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierValidation;

class SupplierValidationController extends Controller
{
    public function index($vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        $validations = $supplier->validations()->with('validator')->latest()->get();
        
        return view('admin.procurement.vendor-validations.index', compact('supplier', 'validations'));
    }

    public function create($vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        $validationTypes = config('categories.validation_types');
        
        return view('admin.procurement.vendor-validations.create', compact('supplier', 'validationTypes'));
    }

    public function store(Request $request, $vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        
        $validated = $request->validate([
            'validation_type' => 'required|string',
            'document_number' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'notes' => 'nullable|string',
            'document_path' => 'nullable|string|max:255',
        ]);

        $validated['supplier_id'] = $supplier->id;
        $validated['status'] = 'pending';

        $validation = SupplierValidation::create($validated);

        return redirect()
            ->route('vendors.validations.index', $vendor)
            ->with('success', 'Validation record created successfully.');
    }

    public function edit($vendor, $validation)
    {
        $supplier = Supplier::findOrFail($vendor);
        $validation = SupplierValidation::where('supplier_id', $supplier->id)->findOrFail($validation);
        $validationTypes = config('categories.validation_types');
        
        return view('admin.procurement.vendor-validations.edit', compact('supplier', 'validation', 'validationTypes'));
    }

    public function update(Request $request, $vendor, $validation)
    {
        $supplier = Supplier::findOrFail($vendor);
        $validation = SupplierValidation::where('supplier_id', $supplier->id)->findOrFail($validation);
        
        $validated = $request->validate([
            'validation_type' => 'required|string',
            'document_number' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'status' => 'required|string|in:valid,expired,pending,rejected',
            'notes' => 'nullable|string',
            'document_path' => 'nullable|string|max:255',
        ]);

        $validation->update($validated);

        return redirect()
            ->route('vendors.validations.index', $vendor)
            ->with('success', 'Validation record updated successfully.');
    }

    public function destroy($vendor, $validation)
    {
        $supplier = Supplier::findOrFail($vendor);
        $validation = SupplierValidation::where('supplier_id', $supplier->id)->findOrFail($validation);
        
        $validation->delete();

        return redirect()
            ->route('vendors.validations.index', $vendor)
            ->with('success', 'Validation record deleted successfully.');
    }

    public function validateDocument(Request $request, $vendor, $validation)
    {
        $supplier = Supplier::findOrFail($vendor);
        $validation = SupplierValidation::where('supplier_id', $supplier->id)->findOrFail($validation);
        
        $validated = $request->validate([
            'status' => 'required|string|in:valid,expired,rejected',
            'notes' => 'nullable|string',
        ]);

        $validation->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);

        return redirect()
            ->route('vendors.validations.index', $vendor)
            ->with('success', 'Document validation completed successfully.');
    }
}
