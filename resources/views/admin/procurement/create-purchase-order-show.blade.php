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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Procurement</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.procurement.create-purchase-order') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Purchase Orders</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">View Purchase Order</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $purchaseOrder->po_number }}</h1>
            <p class="text-gray-600 mt-1">{{ $purchaseOrder->supplier }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit
            </a>
            <a href="{{ route('admin.procurement.create-purchase-order') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Purchase Orders
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class='bx bx-check text-green-400 text-xl'></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- PO Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Purchase Order Details</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">PO Number</label>
                            <p class="text-gray-900 font-medium">{{ $purchaseOrder->po_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Supplier</label>
                            <p class="text-gray-900">{{ $purchaseOrder->supplier }}</p>
                        </div>
                        @if ($purchaseOrder->supplier_contact)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Contact Person</label>
                            <p class="text-gray-900">{{ $purchaseOrder->supplier_contact }}</p>
                        </div>
                        @endif
                        @if ($purchaseOrder->supplier_email)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-900">{{ $purchaseOrder->supplier_email }}</p>
                        </div>
                        @endif
                        @if ($purchaseOrder->supplier_phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                            <p class="text-gray-900">{{ $purchaseOrder->supplier_phone }}</p>
                        </div>
                        @endif
                        @if ($purchaseOrder->billing_address)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Billing Address</label>
                            <p class="text-gray-900">{{ $purchaseOrder->billing_address }}</p>
                        </div>
                        @endif
                        @if ($purchaseOrder->shipping_address)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Shipping Address</label>
                            <p class="text-gray-900">{{ $purchaseOrder->shipping_address }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if ($purchaseOrder->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                        <p class="text-gray-900">{{ $purchaseOrder->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Financial Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Financial Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900 font-medium">₱{{ number_format($purchaseOrder->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tax Amount</span>
                            <span class="text-gray-900 font-medium">₱{{ number_format($purchaseOrder->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Shipping Cost</span>
                            <span class="text-gray-900 font-medium">₱{{ number_format($purchaseOrder->shipping_cost, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                                <span class="text-2xl font-bold text-blue-600">₱{{ number_format($purchaseOrder->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">₱{{ number_format($purchaseOrder->total_amount, 2) }}</div>
                            <div class="text-sm text-gray-500 mt-1">Total Value</div>
                        </div>
                        <div class="text-center">
                            @if ($purchaseOrder->status === 'Draft')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @elseif ($purchaseOrder->status === 'Sent')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Sent
                                </span>
                            @elseif ($purchaseOrder->status === 'Approved')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @elseif ($purchaseOrder->status === 'Rejected')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @elseif ($purchaseOrder->status === 'Partially Received')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Partially Received
                                </span>
                            @elseif ($purchaseOrder->status === 'Received')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Received
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Cancelled
                                </span>
                            @endif
                            <div class="text-sm text-gray-500 mt-1">Current Status</div>
                        </div>
                    </div>
                    
                    <!-- Priority Indicator -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Priority Level</span>
                            <span>{{ $purchaseOrder->priority }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @if ($purchaseOrder->priority === 'Urgent')
                                <div class="bg-red-500 h-3 rounded-full" style="width: 100%"></div>
                            @elseif ($purchaseOrder->priority === 'High')
                                <div class="bg-orange-500 h-3 rounded-full" style="width: 75%"></div>
                            @elseif ($purchaseOrder->priority === 'Medium')
                                <div class="bg-yellow-500 h-3 rounded-full" style="width: 50%"></div>
                            @else
                                <div class="bg-green-500 h-3 rounded-full" style="width: 25%"></div>
                            @endif
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            @if ($purchaseOrder->priority === 'Urgent')
                                Immediate attention required - Critical business need
                            @elseif ($purchaseOrder->priority === 'High')
                                High priority - Important for operations
                            @elseif ($purchaseOrder->priority === 'Medium')
                                Normal priority - Standard processing time
                            @else
                                Low priority - Can be processed when resources available
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Purchase Order Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Order Created</p>
                                <p class="text-sm text-gray-600">{{ $purchaseOrder->order_date->format('M d, Y') }} by {{ $purchaseOrder->created_by }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Expected Delivery</p>
                                <p class="text-sm text-gray-600">{{ $purchaseOrder->expected_delivery_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        @if ($purchaseOrder->actual_delivery_date)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Actual Delivery</p>
                                <p class="text-sm text-gray-600">{{ $purchaseOrder->actual_delivery_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if ($purchaseOrder->approved_by)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Approved By</p>
                                <p class="text-sm text-gray-600">{{ $purchaseOrder->approved_by }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-gray-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-sm text-gray-600">{{ $purchaseOrder->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                        <i class='bx bx-edit text-blue-600 text-xl'></i>
                        <div>
                            <p class="font-medium text-gray-900">Edit Purchase Order</p>
                            <p class="text-sm text-gray-600">Update PO details</p>
                        </div>
                    </a>
                    
                    @if ($purchaseOrder->status === 'Draft')
                    <form action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Sent">
                        <button type="submit" class="w-full text-left px-4 py-3 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-send text-blue-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-blue-900">Send to Supplier</p>
                                <p class="text-sm text-blue-600">Mark as sent</p>
                            </div>
                        </button>
                    </form>
                    @endif
                    
                    @if ($purchaseOrder->status === 'Sent')
                    <form action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="w-full text-left px-4 py-3 border border-green-200 rounded-lg hover:bg-green-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-check text-green-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-green-900">Approve PO</p>
                                <p class="text-sm text-green-600">Mark as approved</p>
                            </div>
                        </button>
                    </form>
                    @endif
                    
                    @if ($purchaseOrder->status === 'Approved')
                    <form action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Received">
                        <button type="submit" class="w-full text-left px-4 py-3 border border-green-200 rounded-lg hover:bg-green-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-check-double text-green-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-green-900">Mark as Received</p>
                                <p class="text-sm text-green-600">Complete this PO</p>
                            </div>
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('purchase-orders.destroy', $purchaseOrder->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this purchase order? This action cannot be undone.')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-trash text-red-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-red-900">Delete PO</p>
                                <p class="text-sm text-red-600">Remove from system</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">System Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                        <p class="text-gray-900">{{ $purchaseOrder->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                        <p class="text-gray-900">{{ $purchaseOrder->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID</label>
                        <p class="text-gray-900 font-mono text-sm">#{{ $purchaseOrder->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
