@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                    <i class='bx bx-home text-xl mr-2'></i>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Logistic Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('delivery-confirmation.index') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Delivery Confirmation</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">New Delivery Confirmation</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">New Delivery Confirmation</h1>
        <a href="{{ route('delivery-confirmation.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Deliveries
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Delivery Information</h2>
        </div>
        
        <form method="POST" action="{{ route('delivery-confirmation.store') }}" class="p-6">
            @csrf
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class='bx bx-error text-red-400 text-xl'></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order Number -->
                <div>
                    <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">Order Number</label>
                    <input type="text" 
                           id="order_number" 
                           name="order_number" 
                           value="{{ old('order_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., ORD-2024-001">
                    <p class="mt-1 text-sm text-gray-500">Optional: Related order number</p>
                </div>

                <!-- Tracking Number -->
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <input type="text" 
                           id="tracking_number" 
                           name="tracking_number" 
                           value="{{ old('tracking_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., TRK123456789">
                    <p class="mt-1 text-sm text-gray-500">Optional: Carrier tracking number</p>
                </div>

                <!-- Recipient Name -->
                <div>
                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">Recipient Name *</label>
                    <input type="text" 
                           id="recipient_name" 
                           name="recipient_name" 
                           value="{{ old('recipient_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Smith"
                           required>
                </div>

                <!-- Recipient Email -->
                <div>
                    <label for="recipient_email" class="block text-sm font-medium text-gray-700 mb-2">Recipient Email</label>
                    <input type="email" 
                           id="recipient_email" 
                           name="recipient_email" 
                           value="{{ old('recipient_email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., john@example.com">
                    <p class="mt-1 text-sm text-gray-500">Optional: For notifications</p>
                </div>

                <!-- Recipient Phone -->
                <div>
                    <label for="recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">Recipient Phone</label>
                    <input type="tel" 
                           id="recipient_phone" 
                           name="recipient_phone" 
                           value="{{ old('recipient_phone') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., (555) 123-4567">
                    <p class="mt-1 text-sm text-gray-500">Optional: For contact</p>
                </div>

                <!-- Delivery Type -->
                <div>
                    <label for="delivery_type" class="block text-sm font-medium text-gray-700 mb-2">Delivery Type *</label>
                    <select id="delivery_type" 
                            name="delivery_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select delivery type</option>
                        <option value="Standard" {{ old('delivery_type') === 'Standard' ? 'selected' : '' }}>Standard</option>
                        <option value="Express" {{ old('delivery_type') === 'Express' ? 'selected' : '' }}>Express</option>
                        <option value="Overnight" {{ old('delivery_type') === 'Overnight' ? 'selected' : '' }}>Overnight</option>
                        <option value="Same Day" {{ old('delivery_type') === 'Same Day' ? 'selected' : '' }}>Same Day</option>
                        <option value="Scheduled" {{ old('delivery_type') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select status</option>
                        <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Out for Delivery" {{ old('status') === 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="Delivered" {{ old('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Failed" {{ old('status') === 'Failed' ? 'selected' : '' }}>Failed</option>
                        <option value="Cancelled" {{ old('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select priority</option>
                        <option value="Urgent" {{ old('priority') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <!-- Package Count -->
                <div>
                    <label for="package_count" class="block text-sm font-medium text-gray-700 mb-2">Package Count *</label>
                    <input type="number" 
                           id="package_count" 
                           name="package_count" 
                           value="{{ old('package_count', 1) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="1"
                           min="1"
                           required>
                </div>

                <!-- Package Value -->
                <div>
                    <label for="package_value" class="block text-sm font-medium text-gray-700 mb-2">Package Value</label>
                    <input type="number" 
                           id="package_value" 
                           name="package_value" 
                           value="{{ old('package_value') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0">
                    <p class="mt-1 text-sm text-gray-500">Optional: Total value in PHP</p>
                </div>

                <!-- Scheduled Delivery Date -->
                <div>
                    <label for="scheduled_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Scheduled Delivery Date</label>
                    <input type="date" 
                           id="scheduled_delivery_date" 
                           name="scheduled_delivery_date" 
                           value="{{ old('scheduled_delivery_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: When delivery is scheduled</p>
                </div>

                <!-- Actual Delivery Time -->
                <div>
                    <label for="actual_delivery_time" class="block text-sm font-medium text-gray-700 mb-2">Actual Delivery Time</label>
                    <input type="datetime-local" 
                           id="actual_delivery_time" 
                           name="actual_delivery_time" 
                           value="{{ old('actual_delivery_time') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: When delivery was completed</p>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Delivery Address</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                        <input type="text" 
                               id="delivery_address" 
                               name="delivery_address" 
                               value="{{ old('delivery_address') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., 123 Main Street, Apt 4B"
                               required>
                    </div>

                    <div>
                        <label for="delivery_city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                        <input type="text" 
                               id="delivery_city" 
                               name="delivery_city" 
                               value="{{ old('delivery_city') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., New York"
                               required>
                    </div>

                    <div>
                        <label for="delivery_state" class="block text-sm font-medium text-gray-700 mb-2">State/Province *</label>
                        <input type="text" 
                               id="delivery_state" 
                               name="delivery_state" 
                               value="{{ old('delivery_state') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., NY"
                               required>
                    </div>

                    <div>
                        <label for="delivery_zipcode" class="block text-sm font-medium text-gray-700 mb-2">ZIP/Postal Code *</label>
                        <input type="text" 
                               id="delivery_zipcode" 
                               name="delivery_zipcode" 
                               value="{{ old('delivery_zipcode') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., 10001"
                               required>
                    </div>

                    <div>
                        <label for="delivery_country" class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                        <input type="text" 
                               id="delivery_country" 
                               name="delivery_country" 
                               value="{{ old('delivery_country', 'USA') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., USA"
                               required>
                    </div>
                </div>
            </div>

            <!-- Carrier Information -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Carrier Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="carrier_name" class="block text-sm font-medium text-gray-700 mb-2">Carrier Name</label>
                        <input type="text" 
                               id="carrier_name" 
                               name="carrier_name" 
                               value="{{ old('carrier_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., FedEx, UPS, USPS">
                        <p class="mt-1 text-sm text-gray-500">Optional: Delivery carrier</p>
                    </div>

                    <div>
                        <label for="driver_name" class="block text-sm font-medium text-gray-700 mb-2">Driver Name</label>
                        <input type="text" 
                               id="driver_name" 
                               name="driver_name" 
                               value="{{ old('driver_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., John Driver">
                        <p class="mt-1 text-sm text-gray-500">Optional: Driver name</p>
                    </div>

                    <div>
                        <label for="driver_phone" class="block text-sm font-medium text-gray-700 mb-2">Driver Phone</label>
                        <input type="tel" 
                               id="driver_phone" 
                               name="driver_phone" 
                               value="{{ old('driver_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., (555) 123-4567">
                        <p class="mt-1 text-sm text-gray-500">Optional: Driver contact</p>
                    </div>

                    <div>
                        <label for="created_by" class="block text-sm font-medium text-gray-700 mb-2">Created By *</label>
                        <input type="text" 
                               id="created_by" 
                               name="created_by" 
                               value="{{ old('created_by') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Admin User"
                               required>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">Special Instructions</label>
                        <textarea id="special_instructions" 
                                  name="special_instructions" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter any special delivery instructions...">{{ old('special_instructions') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional: Special handling instructions</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">Delivery Notes</label>
                        <textarea id="delivery_notes" 
                                  name="delivery_notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter delivery notes or comments...">{{ old('delivery_notes') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional: Additional delivery information</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('delivery-confirmation.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Delivery Confirmation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-set actual delivery time when status changes to 'Delivered'
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const actualDeliveryTime = document.getElementById('actual_delivery_time');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'Delivered' && !actualDeliveryTime.value) {
            const now = new Date();
            const offset = now.getTimezoneOffset() * 60000; // Convert to minutes
            const localISOTime = new Date(now - offset).toISOString().slice(0, 16);
            actualDeliveryTime.value = localISOTime;
        }
    });
});
</script>
@endsection
