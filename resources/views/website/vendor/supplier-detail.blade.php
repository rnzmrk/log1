@extends('website.layouts.app')

@section('title', $supplier->company_name . ' - Supplier Profile')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Supplier Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-20 w-20">
                        @if($supplier->logo)
                            <img class="h-20 w-20 rounded-full object-cover border-4 border-white" src="{{ asset('storage/vendors/' . $supplier->logo) }}" alt="{{ $supplier->name }}">
                        @else
                            <div class="h-20 w-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center border-4 border-white">
                                <i class='bx bx-store text-white text-3xl'></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-6">
                        <h1 class="text-3xl font-bold text-white">{{ $supplier->name }}</h1>
                        <p class="text-blue-100 text-lg mt-1">{{ $supplier->category }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <div class="flex items-center">
                                <div class="flex text-yellow-300">
                                    <i class='bx bx-star'></i>
                                    <i class='bx bx-star'></i>
                                    <i class='bx bx-star'></i>
                                    <i class='bx bx-star'></i>
                                    <i class='bx bx-star'></i>
                                </div>
                                <span class="ml-2 text-blue-100">(5.0 • 127 reviews)</span>
                            </div>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                                {{ $supplier->status }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button onclick="contactSupplier()" class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 font-medium">
                        <i class='bx bx-envelope mr-2'></i>
                        Contact Supplier
                    </button>
                    <button onclick="viewProducts()" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-medium">
                        <i class='bx bx-package mr-2'></i>
                        View Products
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Info -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">About {{ $supplier->name }}</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $supplier->description }}</p>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-map text-blue-600 text-xl'></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Location</p>
                                <p class="text-gray-600">{{ $supplier->address }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-calendar text-green-600 text-xl'></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Member Since</p>
                                <p class="text-gray-600">{{ $supplier->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-package text-purple-600 text-xl'></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Products</p>
                                <p class="text-gray-600">{{ $supplier->products()->count() }} items listed</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-check-circle text-yellow-600 text-xl'></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Verification</p>
                                <p class="text-gray-600">Verified Supplier</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Products</h2>
                        <a href="{{ route('website.supplier.products', $supplier->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            View All →
                        </a>
                    </div>
                    
                    @if($supplier->products()->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($supplier->products()->take(4) as $product)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($product->image)
                                                <img class="h-16 w-16 rounded-lg object-cover" src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
                                            @else
                                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <i class='bx bx-package text-gray-400 text-xl'></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $product->sku }}</p>
                                            <div class="flex items-center justify-between mt-2">
                                                <span class="text-sm font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    @if($product->stock_quantity > 10) bg-green-100 text-green-800
                                                    @elseif($product->stock_quantity > 0) bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $product->stock_quantity }} in stock
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class='bx bx-package text-4xl text-gray-300 mb-4'></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No products listed</h3>
                            <p class="text-gray-500">This supplier hasn't added any products yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Reviews Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Customer Reviews</h2>
                    
                    <!-- Rating Summary -->
                    <div class="flex items-center space-x-8 mb-8">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-gray-900">5.0</div>
                            <div class="flex text-yellow-400 mt-1">
                                <i class='bx bx-star'></i>
                                <i class='bx bx-star'></i>
                                <i class='bx bx-star'></i>
                                <i class='bx bx-star'></i>
                                <i class='bx bx-star'></i>
                            </div>
                            <p class="text-gray-500 mt-1">Based on 127 reviews</p>
                        </div>
                        
                        <div class="flex-1">
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 w-12">5 star</span>
                                    <div class="flex-1 mx-3 bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $supplier->rating ? '80%' : '0%' }}"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12">{{ $supplier->rating ? '80' : '0' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 w-12">4 star</span>
                                    <div class="flex-1 mx-3 bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $supplier->rating ? '15%' : '0%' }}"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12">{{ $supplier->rating ? '15' : '0' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 w-12">3 star</span>
                                    <div class="flex-1 mx-3 bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $supplier->rating ? '5%' : '0%' }}"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12">{{ $supplier->rating ? '5' : '0' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 w-12">2 star</span>
                                    <div class="flex-1 mx-3 bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12">0</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 w-12">1 star</span>
                                    <div class="flex-1 mx-3 bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                                    </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class='bx bx-user text-gray-400 text-xl mr-3'></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Contact Person</p>
                                <p class="text-gray-600">{{ $supplier->contact_person }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class='bx bx-envelope text-gray-400 text-xl mr-3'></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Email</p>
                                <p class="text-gray-600">{{ $supplier->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class='bx bx-phone text-gray-400 text-xl mr-3'></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Phone</p>
                                <p class="text-gray-600">{{ $supplier->phone }}</p>
                            </div>
                        </div>
                        
                        @if($supplier->website)
                        <div class="flex items-center">
                            <i class='bx bx-globe text-gray-400 text-xl mr-3'></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Website</p>
                                <a href="{{ $supplier->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                    {{ parse_url($supplier->website, PHP_URL_HOST) }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <button onclick="contactSupplier()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            <i class='bx bx-envelope mr-2'></i>
                            Send Message
                        </button>
                        <button onclick="callSupplier()" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                            <i class='bx bx-phone mr-2'></i>
                            Call Now
                        </button>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Hours</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Monday - Friday</span>
                            <span class="text-gray-900">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Saturday</span>
                            <span class="text-gray-900">9:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sunday</span>
                            <span class="text-gray-900">Closed</span>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Categories</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $supplier->category }}</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Computer Equipment</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">Office Supplies</span>
                    </div>
                </div>

                <!-- Admin Actions -->
                @if(auth()->guard('admin')->check())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.vendor.edit', $supplier->id) }}" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium text-center block">
                            <i class='bx bx-edit mr-2'></i>
                            Edit Supplier
                        </a>
                        <button onclick="toggleStatus()" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium">
                            <i class='bx bx-toggle-left mr-2'></i>
                            Toggle Status
                        </button>
                        <button onclick="deleteSupplier()" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                            <i class='bx bx-trash mr-2'></i>
                            Delete Supplier
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div id="contactModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-0 border shadow-2xl rounded-2xl bg-white max-w-md w-full">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl p-6 text-white">
            <h3 class="text-xl font-bold flex items-center gap-3">
                <i class='bx bx-envelope text-xl'></i>
                Contact {{ $supplier->name }}
            </h3>
        </div>
        <form class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your email">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your message..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeContactModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function contactSupplier() {
    document.getElementById('contactModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function callSupplier() {
    window.location.href = 'tel:{{ $supplier->phone }}';
}

function viewProducts() {
    window.location.href = '{{ route('website.supplier.products', $supplier->id) }}';
}

function toggleStatus() {
    if (confirm('Are you sure you want to toggle this supplier\'s status?')) {
        // Implement toggle status functionality
        console.log('Toggle status');
    }
}

function deleteSupplier() {
    if (confirm('Are you sure you want to delete this supplier? This action cannot be undone.')) {
        // Implement delete functionality
        console.log('Delete supplier');
    }
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeContactModal();
    }
});
</script>

@endsection
