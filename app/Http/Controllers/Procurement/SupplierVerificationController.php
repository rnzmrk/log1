<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierVerification;

class SupplierVerificationController extends Controller
{
    public function index($vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        $verifications = $supplier->verifications()->with('verifier', 'scheduler')->latest()->get();
        
        return view('admin.procurement.vendor-verifications.index', compact('supplier', 'verifications'));
    }

    public function create($vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        $verificationTypes = config('categories.verification_types');
        
        return view('admin.procurement.vendor-verifications.create', compact('supplier', 'verificationTypes'));
    }

    public function store(Request $request, $vendor)
    {
        $supplier = Supplier::findOrFail($vendor);
        
        $validated = $request->validate([
            'verification_type' => 'required|string',
            'verification_date' => 'required|date',
            'status' => 'required|string|in:passed,failed,pending,scheduled',
            'score' => 'nullable|integer|min:0|max:100',
            'findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'report_path' => 'nullable|string|max:255',
        ]);

        $validated['supplier_id'] = $supplier->id;
        $validated['scheduled_by'] = auth()->id();

        $verification = SupplierVerification::create($validated);

        return redirect()
            ->route('vendors.verifications.index', $vendor)
            ->with('success', 'Verification scheduled successfully.');
    }

    public function edit($vendor, $verification)
    {
        $supplier = Supplier::findOrFail($vendor);
        $verification = SupplierVerification::where('supplier_id', $supplier->id)->findOrFail($verification);
        $verificationTypes = config('categories.verification_types');
        
        return view('admin.procurement.vendor-verifications.edit', compact('supplier', 'verification', 'verificationTypes'));
    }

    public function update(Request $request, $vendor, $verification)
    {
        $supplier = Supplier::findOrFail($vendor);
        $verification = SupplierVerification::where('supplier_id', $supplier->id)->findOrFail($verification);
        
        $validated = $request->validate([
            'verification_type' => 'required|string',
            'verification_date' => 'required|date',
            'status' => 'required|string|in:passed,failed,pending,scheduled',
            'score' => 'nullable|integer|min:0|max:100',
            'findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'report_path' => 'nullable|string|max:255',
        ]);

        $verification->update($validated);

        return redirect()
            ->route('vendors.verifications.index', $vendor)
            ->with('success', 'Verification updated successfully.');
    }

    public function destroy($vendor, $verification)
    {
        $supplier = Supplier::findOrFail($vendor);
        $verification = SupplierVerification::where('supplier_id', $supplier->id)->findOrFail($verification);
        
        $verification->delete();

        return redirect()
            ->route('vendors.verifications.index', $vendor)
            ->with('success', 'Verification deleted successfully.');
    }

    public function complete(Request $request, $vendor, $verification)
    {
        $supplier = Supplier::findOrFail($vendor);
        $verification = SupplierVerification::where('supplier_id', $supplier->id)->findOrFail($verification);
        
        $validated = $request->validate([
            'status' => 'required|string|in:passed,failed',
            'score' => 'nullable|integer|min:0|max:100',
            'findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        $verification->update([
            'status' => $validated['status'],
            'score' => $validated['score'],
            'findings' => $validated['findings'],
            'recommendations' => $validated['recommendations'],
            'verified_by' => auth()->id(),
        ]);

        return redirect()
            ->route('vendors.verifications.index', $vendor)
            ->with('success', 'Verification completed successfully.');
    }

    public function schedule(Request $request, $vendor, $verification)
    {
        $supplier = Supplier::findOrFail($vendor);
        $verification = SupplierVerification::where('supplier_id', $supplier->id)->findOrFail($verification);
        
        $validated = $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        $verification->update([
            'status' => 'scheduled',
            'scheduled_at' => $validated['scheduled_at'],
            'scheduled_by' => auth()->id(),
        ]);

        return redirect()
            ->route('vendors.verifications.index', $vendor)
            ->with('success', 'Verification rescheduled successfully.');
    }
}
