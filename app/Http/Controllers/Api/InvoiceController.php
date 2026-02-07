<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of all invoices.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $invoices = Invoice::with(['vendor', 'purchaseOrder', 'items'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $invoices,
                'message' => 'Invoices retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve invoices: ' . $e->getMessage()
            ], 500);
        }
    }
}
