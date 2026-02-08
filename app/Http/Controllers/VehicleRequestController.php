<?php

namespace App\Http\Controllers;

use App\Models\VehicleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class VehicleRequestController extends Controller
{
    /**
     * Display a listing of the vehicle requests.
     */
    public function index()
    {
        $vehicleRequests = VehicleRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.vehicle-requests.index', compact('vehicleRequests'));
    }

    /**
     * Show the form for creating a new vehicle request.
     */
    public function create()
    {
        return view('admin.vehicle-requests.create');
    }

    /**
     * Store a newly created vehicle request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|integer',
        ]);

        try {
            // Fetch data from the API
            $response = Http::get('https://log2.imarketph.com/api/dispatch');
            
            if (!$response->successful()) {
                return redirect()->back()
                    ->with('error', 'Failed to fetch data from vehicle API. Please try again.');
            }

            $apiData = $response->json();
            
            // Find the reservation with matching ID
            $reservation = null;
            if (isset($apiData['reservations'])) {
                foreach ($apiData['reservations'] as $res) {
                    if ($res['id'] == $request->reservation_id) {
                        $reservation = $res;
                        break;
                    }
                }
            }

            if (!$reservation) {
                return redirect()->back()
                    ->with('error', 'Reservation not found in the system.');
            }

            // Check if this reservation already exists
            $existingRequest = VehicleRequest::where('reservation_id', $reservation['id'])->first();
            if ($existingRequest) {
                return redirect()->back()
                    ->with('error', 'This reservation has already been requested.');
            }

            // Create the vehicle request
            $vehicleRequest = VehicleRequest::create([
                'reservation_id' => $reservation['id'],
                'vehicle_id' => $reservation['vehicle_id'],
                'reserved_by' => $reservation['reserved_by'],
                'start_time' => $reservation['start_time'],
                'end_time' => $reservation['end_time'],
                'purpose' => $reservation['purpose'],
                'status' => $reservation['status'],
                'department' => $reservation['department'],
                'api_data' => $reservation,
                'request_status' => VehicleRequest::STATUS_PENDING,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('vehicle-requests.index')
                ->with('success', 'Vehicle request created successfully! Your request is now pending approval.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating your request: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified vehicle request.
     */
    public function show(VehicleRequest $vehicleRequest)
    {
        // Check if user owns this request
        if ($vehicleRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.vehicle-requests.show', compact('vehicleRequest'));
    }

    /**
     * Update request status from API
     */
    public function updateStatus()
    {
        try {
            // Fetch current data from API
            $response = Http::get('https://log2.imarketph.com/api/dispatch');
            
            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to fetch API data'], 500);
            }

            $apiData = $response->json();
            $updatedCount = 0;

            if (isset($apiData['reservations'])) {
                foreach ($apiData['reservations'] as $reservation) {
                    // Find corresponding request in our database
                    $vehicleRequest = VehicleRequest::where('reservation_id', $reservation['id'])->first();
                    
                    if ($vehicleRequest && $vehicleRequest->status !== $reservation['status']) {
                        // Update the status
                        $vehicleRequest->update([
                            'status' => $reservation['status'],
                            'api_data' => $reservation,
                        ]);
                        $updatedCount++;
                    }
                }
            }

            return response()->json([
                'message' => "Updated {$updatedCount} vehicle request statuses.",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for reservations by ID
     */
    public function searchReservation(Request $request)
    {
        $reservationId = $request->get('q');
        
        if (!$reservationId) {
            return response()->json([]);
        }

        try {
            $response = Http::get('https://log2.imarketph.com/api/dispatch');
            
            if (!$response->successful()) {
                return response()->json(['error' => 'API unavailable'], 500);
            }

            $apiData = $response->json();
            $results = [];

            if (isset($apiData['reservations'])) {
                foreach ($apiData['reservations'] as $reservation) {
                    // Search by ID or reserved_by name
                    if (stripos($reservation['id'], $reservationId) !== false || 
                        stripos($reservation['reserved_by'], $reservationId) !== false) {
                        $results[] = [
                            'id' => $reservation['id'],
                            'reserved_by' => $reservation['reserved_by'],
                            'vehicle_id' => $reservation['vehicle_id'],
                            'start_time' => $reservation['start_time'],
                            'end_time' => $reservation['end_time'],
                            'purpose' => $reservation['purpose'],
                            'status' => $reservation['status'],
                            'department' => $reservation['department'],
                        ];
                    }
                }
            }

            return response()->json($results);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
