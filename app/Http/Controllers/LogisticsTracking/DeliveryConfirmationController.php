<?php

namespace App\Http\Controllers\LogisticsTracking;

use App\Http\Controllers\Controller;
use App\Models\DeliveryConfirmation;
use Illuminate\Http\Request;

class DeliveryConfirmationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DeliveryConfirmation::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('confirmation_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('order_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('tracking_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('recipient_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('recipient_email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('delivery_address', 'like', '%' . $searchTerm . '%')
                  ->orWhere('delivery_city', 'like', '%' . $searchTerm . '%')
                  ->orWhere('carrier_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by delivery type
        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by carrier
        if ($request->filled('carrier')) {
            $query->where('carrier_name', 'like', '%' . $request->carrier . '%');
        }

        $deliveryConfirmations = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.logistictracking.delivery-confirmation', compact('deliveryConfirmations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.logistictracking.delivery-confirmation-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_phone' => 'nullable|string|max:255',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string|max:255',
            'delivery_state' => 'required|string|max:255',
            'delivery_zipcode' => 'required|string|max:20',
            'delivery_country' => 'required|string|max:255',
            'delivery_type' => 'required|in:Standard,Express,Overnight,Same Day,Scheduled',
            'status' => 'required|in:Pending,Out for Delivery,Delivered,Failed,Cancelled',
            'scheduled_delivery_date' => 'nullable|date',
            'actual_delivery_time' => 'nullable|datetime',
            'delivery_notes' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'carrier_name' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:255',
            'signature_image_url' => 'nullable|string|max:500',
            'package_value' => 'nullable|numeric|min:0|max:999999999999.99',
            'package_count' => 'required|integer|min:1',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'created_by' => 'required|string|max:255',
        ]);

        // Auto-generate confirmation number
        $validated['confirmation_number'] = 'DC-' . date('Y') . '-' . str_pad(DeliveryConfirmation::count() + 1, 4, '0', STR_PAD_LEFT);

        DeliveryConfirmation::create($validated);

        return redirect()->route('delivery-confirmation.index')
            ->with('success', 'Delivery confirmation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deliveryConfirmation = DeliveryConfirmation::findOrFail($id);
        return view('admin.logistictracking.delivery-confirmation-show', compact('deliveryConfirmation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $deliveryConfirmation = DeliveryConfirmation::findOrFail($id);
        return view('admin.logistictracking.delivery-confirmation-edit', compact('deliveryConfirmation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $deliveryConfirmation = DeliveryConfirmation::findOrFail($id);

        $validated = $request->validate([
            'confirmation_number' => 'required|string|unique:delivery_confirmations,confirmation_number,'.$id,
            'order_number' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_phone' => 'nullable|string|max:255',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string|max:255',
            'delivery_state' => 'required|string|max:255',
            'delivery_zipcode' => 'required|string|max:20',
            'delivery_country' => 'required|string|max:255',
            'delivery_type' => 'required|in:Standard,Express,Overnight,Same Day,Scheduled',
            'status' => 'required|in:Pending,Out for Delivery,Delivered,Failed,Cancelled',
            'scheduled_delivery_date' => 'nullable|date',
            'actual_delivery_time' => 'nullable|datetime',
            'delivery_notes' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'carrier_name' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:255',
            'signature_image_url' => 'nullable|string|max:500',
            'package_value' => 'nullable|numeric|min:0|max:999999999999.99',
            'package_count' => 'required|integer|min:1',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'created_by' => 'required|string|max:255',
        ]);

        $deliveryConfirmation->update($validated);

        return redirect()->route('delivery-confirmation.index')
            ->with('success', 'Delivery confirmation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deliveryConfirmation = DeliveryConfirmation::findOrFail($id);
        $deliveryConfirmation->delete();

        return redirect()->route('delivery-confirmation.index')
            ->with('success', 'Delivery confirmation deleted successfully.');
    }
}
