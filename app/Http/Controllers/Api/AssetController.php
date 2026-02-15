<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;

class AssetController extends Controller
{
    /**
     * Display a listing of assets.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        return response()->json(Asset::all());
    }

    /**
     * Store a newly created asset request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_name' => 'required|string|max:255',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:255|unique:assets,serial_number',
                'category' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'unit' => 'required|string|max:50',
                'condition' => 'required|in:Excellent,Good,Fair,Poor',
                'status' => 'nullable|in:Available,In Use,Maintenance,Disposal,Disposed',
                'location' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'purchase_date' => 'required|date',
                'purchase_cost' => 'nullable|numeric|min:0',
                'supplier' => 'nullable|string|max:255',
                'warranty_expiry' => 'nullable|date',
                'notes' => 'nullable|string',
                'details' => 'nullable|string',
            ]);

            // Set default status if not provided
            $validated['status'] = $validated['status'] ?? 'Available';

            // Create asset
            $asset = Asset::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asset created successfully.',
                'data' => $asset
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified asset.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $asset = Asset::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $asset
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified asset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $asset = Asset::findOrFail($id);

            $validated = $request->validate([
                'item_name' => 'sometimes|required|string|max:255',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:255|unique:assets,serial_number,' . $id,
                'category' => 'sometimes|required|string|max:255',
                'quantity' => 'sometimes|required|integer|min:1',
                'unit' => 'sometimes|required|string|max:50',
                'condition' => 'sometimes|required|in:Excellent,Good,Fair,Poor',
                'status' => 'sometimes|required|in:Available,In Use,Maintenance,Disposal,Disposed',
                'location' => 'sometimes|required|string|max:255',
                'department' => 'sometimes|required|string|max:255',
                'purchase_date' => 'sometimes|required|date',
                'purchase_cost' => 'nullable|numeric|min:0',
                'supplier' => 'nullable|string|max:255',
                'warranty_expiry' => 'nullable|date',
                'notes' => 'nullable|string',
                'details' => 'nullable|string',
            ]);

            $asset->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asset updated successfully.',
                'data' => $asset
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified asset.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $asset->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get asset statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        try {
            $totalAssets = Asset::count();
            $availableAssets = Asset::where('status', 'Available')->count();
            $inUseAssets = Asset::where('status', 'In Use')->count();
            $maintenanceAssets = Asset::where('status', 'Maintenance')->count();
            $disposalAssets = Asset::where('status', 'Disposal')->count();
            $disposedAssets = Asset::where('status', 'Disposed')->count();

            // Assets by category
            $assetsByCategory = Asset::select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get();

            // Assets by department
            $assetsByDepartment = Asset::select('department', DB::raw('count(*) as count'))
                ->groupBy('department')
                ->orderBy('count', 'desc')
                ->get();

            // Assets by condition
            $assetsByCondition = Asset::select('condition', DB::raw('count(*) as count'))
                ->groupBy('condition')
                ->orderBy('count', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_assets' => $totalAssets,
                    'by_status' => [
                        'available' => $availableAssets,
                        'in_use' => $inUseAssets,
                        'maintenance' => $maintenanceAssets,
                        'disposal' => $disposalAssets,
                        'disposed' => $disposedAssets,
                    ],
                    'by_category' => $assetsByCategory,
                    'by_department' => $assetsByDepartment,
                    'by_condition' => $assetsByCondition,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve asset statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
