@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Supplier Post</h1>
            <p class="text-gray-600 mt-1">Update the supplier post information</p>
        </div>
        <a href="{{ route('admin.supplier.posts.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2">
            <i class='bx bx-arrow-back'></i>
            Back to Posts
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.supplier.posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Post Title *</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $post->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="e.g., Top Computer Supplier for Office Equipment"
                           required>
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Supplier Selection -->
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select id="supplier_id" 
                            name="supplier_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('supplier_id') border-red-500 @enderror"
                            required>
                        <option value="">Select a supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $post->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }} ({{ $supplier->category }})
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Post Category *</label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('category') border-red-500 @enderror"
                            required>
                        <option value="">Select category</option>
                        <option value="Computer Supplier" {{ old('category', $post->category) == 'Computer Supplier' ? 'selected' : '' }}>Computer Supplier</option>
                        <option value="Office Supplies Supplier" {{ old('category', $post->category) == 'Office Supplies Supplier' ? 'selected' : '' }}>Office Supplies Supplier</option>
                        <option value="Bond Paper Supplier" {{ old('category', $post->category) == 'Bond Paper Supplier' ? 'selected' : '' }}>Bond Paper Supplier</option>
                        <option value="Furniture Supplier" {{ old('category', $post->category) == 'Furniture Supplier' ? 'selected' : '' }}>Furniture Supplier</option>
                        <option value="Equipment Supplier" {{ old('category', $post->category) == 'Equipment Supplier' ? 'selected' : '' }}>Equipment Supplier</option>
                        <option value="Software Supplier" {{ old('category', $post->category) == 'Software Supplier' ? 'selected' : '' }}>Software Supplier</option>
                        <option value="Featured Supplier" {{ old('category', $post->category) == 'Featured Supplier' ? 'selected' : '' }}>Featured Supplier</option>
                    </select>
                    @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                    <textarea id="content" 
                              name="content" 
                              rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none @error('content') border-red-500 @enderror"
                              placeholder="Write about the supplier, their products, services, and why customers should choose them..."
                              required>{{ old('content', $post->content) }}</textarea>
                    @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Post Image</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img id="imagePreview" src="{{ $post->image ? asset($post->image) : 'https://via.placeholder.com/120x120/e5e7eb/6b7280?text=Image' }}" alt="Image preview" class="h-30 w-30 object-cover rounded-lg border border-gray-300">
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('image') border-red-500 @enderror"
                                   onchange="previewImage(event)">
                            <p class="mt-1 text-sm text-gray-500">Upload post image (JPG, PNG, GIF - Max 2MB). Leave empty to keep current image.</p>
                            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('status') border-red-500 @enderror"
                            required>
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Featured -->
                <div>
                    <label class="flex items-center mt-6">
                        <input type="checkbox" 
                               id="featured" 
                               name="featured" 
                               value="1"
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                               {{ old('featured', $post->featured) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Feature this post on homepage</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.supplier.posts.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    <i class='bx bx-x mr-2'></i>Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200 font-medium">
                    <i class='bx bx-save mr-2'></i>Update Post
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
