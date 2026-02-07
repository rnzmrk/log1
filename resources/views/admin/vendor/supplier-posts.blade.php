@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Enhanced Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Supplier Posts</h1>
                <p class="text-gray-600 text-lg">Create and manage posts about suppliers</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.vendor.management') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-sm">
                    <i class='bx bx-arrow-back text-xl'></i>
                    Supplier Management Post
                </a>
                <a href="{{ route('admin.supplier.posts.create') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium py-2 px-6 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class='bx bx-plus text-xl'></i>
                    Create Post
                </a>
            </div>
        </div>
        
        @php
    $totalPosts = $posts->total();
    $publishedPosts = $posts->getCollection()->where('status', 'published')->count();
    $draftPosts = $posts->getCollection()->where('status', 'draft')->count();
    $totalViews = $posts->getCollection()->sum('views');
@endphp
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Posts</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalPosts }}</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-news text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Published</p>
                        <p class="text-3xl font-bold mt-1">{{ $publishedPosts }}</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-check-circle text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Draft</p>
                        <p class="text-3xl font-bold mt-1">{{ $draftPosts }}</p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-edit text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Views</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalViews) }}</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-show text-2xl'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-list-ul text-xl text-purple-600'></i>
                    Supplier Posts
                </h2>
                <div class="flex items-center gap-4">
                    <form method="GET" class="flex items-center gap-2">
                        <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Posts</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        
                        <select name="category" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Categories</option>
                            @foreach($filterOptions['categories'] as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                        
                        @if(request()->anyFilled('status', 'category'))
                            <a href="{{ route('admin.supplier.posts.index') }}" class="text-gray-500 hover:text-gray-700">
                                <i class='bx bx-x'></i> Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts ?? [] as $post)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($post->image)
                                        <img class="h-12 w-12 rounded-lg object-cover mr-3" src="{{ asset($post->image) }}" alt="{{ $post->title }}">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                            <i class='bx bx-image text-gray-400 text-xl'></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($post->content, 50) }}</div>
                                        @if($post->featured)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                <i class='bx bx-star mr-1'></i>Featured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        @if($post->supplier && $post->supplier->logo)
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/vendors/' . $post->supplier->logo) }}" alt="{{ $post->supplier->company_name }}">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class='bx bx-store text-gray-400 text-sm'></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $post->supplier->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $post->supplier->category ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $post->category ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                                    @if($post->status == 'published') bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300
                                    @elseif($post->status == 'draft') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300
                                    @else bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-300
                                    @endif">
                                    {{ $post->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('website.suppliers.post', $post->slug) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors duration-200" title="View Post">
                                        <i class='bx bx-show text-sm'></i>
                                    </a>
                                    <a href="{{ route('admin.supplier.posts.edit', $post) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition-colors duration-200" title="Edit Post">
                                        <i class='bx bx-edit text-sm'></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.supplier.posts.toggle-featured', $post) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition-colors duration-200" title="Toggle Featured">
                                            <i class='bx bx-star text-sm'></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.supplier.posts.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to delete this post?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors duration-200" title="Delete Post">
                                            <i class='bx bx-trash text-sm'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-news text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No posts found</p>
                                    <p class="text-sm">Create your first supplier post to get started.</p>
                                    <a href="{{ route('admin.supplier.posts.create') }}" class="mt-4 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
                                        <i class='bx bx-plus'></i>
                                        Create First Post
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-6">
        {{ $posts->links() }}
    </div>
    @endif
</div>

@endsection
