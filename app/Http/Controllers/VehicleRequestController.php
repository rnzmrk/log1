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
            $response = Http::withoutVerifying()->get('https://log2.imarketph.com/api/dispatch');
            
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
            $response = Http::withoutVerifying()->get('https://log2.imarketph.com/api/dispatch');
            
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
            $response = Http::withoutVerifying()->get('https://log2.imarketph.com/api/dispatch');
            
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

    /**
     * Get vehicle fleet from external API
     */
    public function getVehicleFleet(Request $request)
    {
        try {
            $dispatchResponse = Http::withoutVerifying()->get('https://log2.imarketph.com/api/dispatch');
            if (!$dispatchResponse->successful()) {
                return response()->json(['error' => 'Failed to fetch vehicle data', 'status' => $dispatchResponse->status()], 500);
            }

            $apiData = $dispatchResponse->json();
            $vehicles = [];

            // Extract ALL vehicles from active_dispatches (no de-duplication)
            if (isset($apiData['active_dispatches']) && is_array($apiData['active_dispatches'])) {
                foreach ($apiData['active_dispatches'] as $dispatch) {
                    if (!isset($dispatch['vehicle']) || !is_array($dispatch['vehicle'])) {
                        continue;
                    }

                    $vehicle = $dispatch['vehicle'];
                    $driver = $dispatch['driver'] ?? null;

                    $vehicles[] = [
                        'id' => $vehicle['id'] ?? null,
                        'plate_no' => $vehicle['plate_no'] ?? null,
                        'model' => $vehicle['model'] ?? null,
                        'type' => $vehicle['type'] ?? null,
                        'engine_no' => $vehicle['engine_no'] ?? null,
                        'chassis_no' => $vehicle['chassis_no'] ?? null,
                        'color' => $vehicle['color'] ?? null,
                        'fuel_type' => $vehicle['fuel_type'] ?? null,
                        'licensePlate' => $vehicle['plate_no'] ?? 'N/A',
                        'vehicleType' => $vehicle['type'] ?? 'N/A',
                        'driver' => is_array($driver) ? ($driver['name'] ?? 'Unassigned') : 'Unassigned',
                        'driverPhone' => is_array($driver) ? ($driver['phone'] ?? 'N/A') : 'N/A',
                        'status' => $this->mapVehicleStatus($vehicle['status'] ?? null),
                        'status_raw' => $vehicle['status'] ?? null,
                        'lastMaintenance' => $vehicle['updated_at'] ?? $vehicle['created_at'] ?? now()->toDateTimeString()
                    ];
                }
            }

            // Pagination - return all vehicles if no limit specified
            $page = $request->get('page', 1);
            $limit = $request->get('limit');
            
            $total = count($vehicles);
            
            if ($limit === null) {
                // Return all vehicles
                $paginatedVehicles = $vehicles;
                $limit = count($vehicles);
            } else {
                // Apply pagination
                $offset = ($page - 1) * $limit;
                $paginatedVehicles = array_slice($vehicles, $offset, $limit);
            }

            return response()->json([
                'data' => $paginatedVehicles,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => $limit ? ceil($total / $limit) : 1
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get vehicle requests from external API
     */
    public function getVehicleRequests(Request $request)
    {
        try {
            $response = Http::withoutVerifying()->get('https://log2.imarketph.com/api/dispatch');
            
            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to fetch vehicle requests', 'status' => $response->status()], 500);
            }

            $apiData = $response->json();
            $requests = [];
            
            if (isset($apiData['active_dispatches'])) {
                foreach ($apiData['active_dispatches'] as $dispatch) {
                    $reservation = $dispatch['reservation'] ?? null;
                    $vehicle = $dispatch['vehicle'] ?? null;
                    $driver = $dispatch['driver'] ?? null;
                    
                    if ($reservation && $vehicle) {
                        $requests[] = [
                            'id' => $reservation['id'],
                            'driver' => $driver['name'] ?? $reservation['reserved_by'] ?? 'N/A',
                            'driverEmail' => $driver['email'] ?? 'N/A',
                            'vehicleType' => $vehicle['type'] ?? 'N/A',
                            'purpose' => $reservation['purpose'] ?? 'N/A',
                            'requestDate' => $reservation['created_at'] ?? now()->toDateTimeString(),
                            'duration' => $this->calculateDuration($reservation['start_time'], $reservation['end_time']),
                            'status' => $reservation['status'] ?? 'pending'
                        ];
                    }
                }
            }

            // Pagination - return all requests if no limit specified
            $page = $request->get('page', 1);
            $limit = $request->get('limit');
            
            $total = count($requests);
            
            if ($limit === null) {
                // Return all requests
                $paginatedRequests = $requests;
                $limit = count($requests);
            } else {
                // Apply pagination
                $offset = ($page - 1) * $limit;
                $paginatedRequests = array_slice($requests, $offset, $limit);
            }

            return response()->json([
                'data' => $paginatedRequests,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => $limit ? ceil($total / $limit) : 1
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new dispatch request
     */
    public function createDispatch(Request $request)
    {
        try {
            // Simple test response
            return response()->json([
                'success' => true,
                'message' => 'createDispatch method works',
                'data' => $request->all()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create dispatch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map vehicle status from API to frontend format
     */
    private function mapVehicleStatus($status)
    {
        $statusMap = [
            'In-Use' => 'in-use',
            'Available' => 'available',
            'Maintenance' => 'maintenance',
            'Out-of-Service' => 'out-of-service',
            'Reserved' => 'reserved',
            'In-Transit' => 'in-use'
        ];
        
        return $statusMap[$status] ?? 'available';
    }

    /**
     * Calculate duration between start and end time
     */
    private function calculateDuration($startTime, $endTime)
    {
        try {
            $start = new \DateTime($startTime);
            $end = new \DateTime($endTime);
            $interval = $start->diff($end);
            
            if ($interval->days > 0) {
                return $interval->days . ' day(s)';
            } elseif ($interval->h > 0) {
                return $interval->h . ' hour(s)';
            } else {
                return $interval->i . ' minute(s)';
            }
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
