@extends('website.layouts.app')

@section('title', 'Supplier Directory - Find Trusted Suppliers')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Find Trusted Suppliers
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Connect with verified suppliers for all your business needs
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('website.suppliers.register') }}" class="bg-blue-500 text-white font-semibold py-3 px-8 rounded-lg hover:bg-blue-400 transition-colors">
                        <i class='bx bx-store mr-2'></i>
                        Become a Supplier
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts Section -->
    @if($featuredPosts->count() > 0)
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Suppliers</h2>
                <p class="text-lg text-gray-600">Discover our top-rated suppliers</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredPosts as $post)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    @if($post->image)
                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-blue-400 to-purple-400 flex items-center justify-center">
                            <i class='bx bx-store text-white text-4xl'></i>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            @if($post->featured)
                                <i class='bx bx-star text-yellow-400 mr-2'></i>
                            @endif
                            <span class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $post->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($post->content, 100) }}</p>
                        @if($post->supplier)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    <i class='bx bx-building mr-1'></i>
                                    {{ $post->supplier->name }}
                                </span>
                                <a href="{{ route('website.suppliers.post', $post->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    Read More →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('website.suppliers.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                    View All Suppliers
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Suppliers Section -->
    @if($featuredSuppliers->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Top Suppliers</h2>
                <p class="text-lg text-gray-600">Highly rated suppliers in various categories</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredSuppliers as $supplier)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-{{ $supplier->avatar_color }}-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            {{ $supplier->initials }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $supplier->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $supplier->category }}</p>
                        </div>
                    </div>
                    
                    @if($supplier->rating)
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($supplier->rating))
                                        <i class='bx bx-star'></i>
                                    @elseif($i == ceil($supplier->rating) && $supplier->rating % 1 != 0)
                                        <i class='bx bx-star-half'></i>
                                    @else
                                        <i class='bx bx-star text-gray-300'></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ $supplier->rating }}/5</span>
                        </div>
                    @endif
                    
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($supplier->description ?? 'Professional supplier offering quality products and services.', 80) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            <i class='bx bx-map-pin mr-1'></i>
                            {{ $supplier->city }}, {{ $supplier->state }}
                        </span>
                        <a href="{{ route('website.suppliers.show', $supplier->slug ?? $supplier->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            View Profile →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Categories Section -->
    @if($categories->count() > 0)
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Browse by Category</h2>
                <p class="text-lg text-gray-600">Find suppliers by your specific needs</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                <a href="{{ route('website.suppliers.index', ['category' => $category]) }}" class="bg-gray-50 rounded-lg p-6 text-center hover:bg-gray-100 transition-colors group">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200 transition-colors">
                        <i class='bx bx-store text-blue-600 text-xl'></i>
                    </div>
                    <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">{{ $category }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ App\Models\Supplier::active()->where('category', $category)->count() }} suppliers</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Join Our Network?</h2>
            <p class="text-xl mb-8 text-blue-100">
                Become a supplier and reach thousands of potential customers
            </p>
            <a href="{{ route('website.suppliers.register') }}" class="bg-white text-blue-600 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition-colors">
                <i class='bx bx-plus-circle mr-2'></i>
                Register Your Business
            </a>
        </div>
    </section>
</div>
@endsection
