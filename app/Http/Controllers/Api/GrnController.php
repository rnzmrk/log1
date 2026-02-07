<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceivedNote;
use Illuminate\Http\Request;

class GrnController extends Controller
{
    /**
     * Display a listing of all GRNs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $grns = GoodsReceivedNote::with(['vendor', 'purchaseOrder', 'items', 'receivedBy'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $grns,
                'message' => 'GRNs retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve GRNs: ' . $e->getMessage()
            ], 500);
        }
    }
}
