@extends('website.layouts.app')

@section('title', 'Suppliers - Find Trusted Vendors')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Find Trusted Suppliers</h1>
                <p class="text-xl text-blue-100 mb-8">Connect with verified suppliers for computers, office supplies, furniture and more</p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <form method="GET" action="{{ route('website.suppliers') }}" class="flex">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class='bx bx-search text-gray-400'></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search suppliers by name, category, or products..." 
                                   class="w-full pl-10 pr-4 py-3 border border-transparent rounded-l-lg focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent text-gray-900">
                        </div>
                        <button type="submit" class="px-6 py-3 bg-white text-blue-600 rounded-r-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white font-medium">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Browse by Category</h2>
            <p class="mt-2 text-gray-600">Find suppliers for your specific needs</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @php
                $categories = App\Models\Supplier::select('category')->whereNotNull('category')->distinct()->pluck('category');
            @endphp
            @foreach($categories as $category)
                <a href="{{ route('website.suppliers.index', ['category' => $category]) }}" class="bg-white rounded-lg p-6 text-center hover:shadow-lg transition-shadow border border-gray-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class='bx bx-store text-blue-600 text-2xl'></i>
                    </div>
                    <h3 class="font-medium text-gray-900">{{ $category }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ App\Models\Supplier::where('category', $category)->count() }} suppliers</p>
                </a>
            @endforeach
            
            <!-- Add Category Button for Admin -->
            @if(auth()->guard('admin')->check())
                <button onclick="openCategoryModal()" class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg p-6 text-center hover:shadow-lg transition-shadow border-2 border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class='bx bx-plus text-gray-600 text-2xl'></i>
                    </div>
                    <h3 class="font-medium text-gray-700">Add Category</h3>
                    <p class="text-sm text-gray-500 mt-1">Custom category</p>
                </button>
            @endif
        </div>
    </div>

    <!-- Featured Suppliers -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Featured Suppliers</h2>
                <p class="mt-2 text-gray-600">Top-rated suppliers in our network</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredPosts ?? [] as $post)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow">
                        @if($post->image)
                            <img src="{{ asset('storage/posts/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                <i class='bx bx-store text-blue-600 text-4xl'></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            @if($post->featured)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mb-2">
                                    <i class='bx bx-star mr-1'></i>Featured
                                </span>
                            @endif
                            
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ $post->excerpt }}</p>
                            
                            @if($post->supplier)
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($post->supplier->logo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/vendors/' . $post->supplier->logo) }}" alt="{{ $post->supplier->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class='bx bx-store text-gray-400 text-sm'></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $post->supplier->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $post->supplier->category }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $post->supplier->status ?? 'Active' }}
                                </span>
                                <a href="{{ route('website.suppliers.post', $post->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    @forelse($suppliers->take(6) as $supplier)
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        @if($supplier->logo)
                                            <img class="h-16 w-16 rounded-full object-cover border-2 border-gray-200" src="{{ asset('storage/vendors/' . $supplier->logo) }}" alt="{{ $supplier->name }}">
                                        @else
                                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border-2 border-gray-200">
                                                <i class='bx bx-store text-blue-600 text-2xl'></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $supplier->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $supplier->category }}</p>
                                        <div class="flex items-center mt-1">
                                            <div class="flex text-yellow-400">
                                                <i class='bx bx-star'></i>
                                                <i class='bx bx-star'></i>
                                                <i class='bx bx-star'></i>
                                                <i class='bx bx-star'></i>
                                                <i class='bx bx-star'></i>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-500">({{ $supplier->rating ?? '5.0' }})</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-4">{{ $supplier->description ? Str::limit($supplier->description, 100) : 'Quality supplier with excellent service and competitive prices.' }}</p>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class='bx bx-user mr-2'></i>
                                        {{ $supplier->contact_person }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class='bx bx-envelope mr-2'></i>
                                        {{ $supplier->email }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class='bx bx-phone mr-2'></i>
                                        {{ $supplier->phone }}
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $supplier->status }}
                                    </span>
                                    <a href="{{ route('website.supplier.detail', $supplier->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <i class='bx bx-store text-4xl text-gray-300 mb-4'></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No featured suppliers yet</h3>
                            <p class="text-gray-500">Check back soon for our top-rated suppliers!</p>
                            @if(auth()->guard('admin')->check())
                                <a href="{{ route('admin.supplier.posts') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <i class='bx bx-plus mr-2'></i>
                                    Create Featured Post
                                </a>
                            @endif
                        </div>
                    @endforelse
                @endforelse
            </div>
        </div>
    </div>

    <!-- All Suppliers -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">All Suppliers</h2>
                <p class="mt-2 text-gray-600">{{ $suppliers->count() }} suppliers in our network</p>
            </div>
            
            <!-- Filters -->
            <div class="flex items-center space-x-4">
                <select name="sort" onchange="window.location.href='?sort='+this.value" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="name">Sort by Name</option>
                    <option value="category">Sort by Category</option>
                    <option value="rating">Sort by Rating</option>
                    <option value="newest">Newest First</option>
                </select>
                
                @if(auth()->guard('admin')->check())
                    <a href="{{ route('admin.vendor.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                        <i class='bx bx-plus mr-2'></i>
                        Add Supplier
                    </a>
                @endif
            </div>
        </div>

        <!-- Supplier Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($suppliers as $supplier)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                @if($supplier->logo)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/vendors/' . $supplier->logo) }}" alt="{{ $supplier->company_name }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class='bx bx-store text-gray-400 text-xl'></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $supplier->company_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $supplier->category }}</p>
                            </div>
                        </div>
                        
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($supplier->status == 'Active') bg-green-100 text-green-800
                            @elseif($supplier->status == 'Pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $supplier->status }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($supplier->description, 80) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class='bx bx-package mr-1'></i>
                            {{ $supplier->products()->count() }} products
                        </div>
                        <a href="{{ route('website.supplier.detail', $supplier->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            View Profile →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <i class='bx bx-search text-4xl text-gray-300 mb-4'></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No suppliers found</h3>
                    <p class="text-gray-500">Try adjusting your search or filters</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($suppliers->hasPages())
            <div class="mt-8">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Become a Supplier</h2>
            <p class="text-xl text-blue-100 mb-8">Join our network and reach thousands of potential customers</p>
            <a href="{{ route('website.vendor.register') }}" class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                <i class='bx bx-store mr-2'></i>
                Register as Supplier
            </a>
        </div>
    </div>
</div>

@endsection
