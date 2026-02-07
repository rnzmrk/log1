<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplierPost;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SupplierPostController extends Controller
{
    /**
     * Display a listing of supplier posts
     */
    public function index(Request $request)
    {
        // Build filters from request
        $filters = [
            'search' => $request->search,
            'status' => $request->status,
            'category' => $request->category,
            'featured' => $request->featured,
        ];

        // Apply filters and paginate
        $query = SupplierPost::with('supplier');
        
        if ($filters['search']) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('content', 'like', "%{$filters['search']}%");
            });
        }
        
        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }
        
        if ($filters['category']) {
            $query->where('category', $filters['category']);
        }
        
        if ($filters['featured'] !== null) {
            $query->where('featured', $filters['featured'] === 'true');
        }

        $posts = $query->latest()->paginate(15);

        // Get filter options
        $filterOptions = [
            'categories' => SupplierPost::distinct('category')->pluck('category')->filter()->sort()->values(),
        ];

        return view('admin.vendor.supplier-posts', compact('posts', 'filterOptions', 'filters'));
    }

    /**
     * Show the form for creating a new supplier post
     */
    public function create()
    {
        $suppliers = Supplier::where('status', 'Active')->orderBy('name')->get();
        $categories = SupplierPost::distinct('category')->pluck('category')->filter()->sort()->values();
        
        return view('admin.vendor.supplier-posts-create', compact('suppliers', 'categories'));
    }

    /**
     * Store a newly created supplier post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'supplier_id' => 'required|exists:vendors,id',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'featured' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/supplier-posts'), $imageName);
            $validated['image'] = 'images/supplier-posts/' . $imageName;
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        
        // Set featured status
        $validated['featured'] = $request->has('featured');
        
        // Set published_at if status is published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }
        
        // Set created_by
        $validated['created_by'] = auth()->id();

        SupplierPost::create($validated);

        return redirect()->route('admin.supplier.posts.index')
            ->with('success', 'Supplier post created successfully.');
    }

    /**
     * Show the form for editing the specified supplier post
     */
    public function edit(SupplierPost $post)
    {
        $suppliers = Supplier::where('status', 'Active')->orderBy('name')->get();
        $categories = SupplierPost::distinct('category')->pluck('category')->filter()->sort()->values();
        
        return view('admin.vendor.supplier-posts-edit', compact('post', 'suppliers', 'categories'));
    }

    /**
     * Update the specified supplier post
     */
    public function update(Request $request, SupplierPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'supplier_id' => 'required|exists:vendors,id',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'featured' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/supplier-posts'), $imageName);
            $validated['image'] = 'images/supplier-posts/' . $imageName;
        }

        // Update slug if title changed
        if ($validated['title'] !== $post->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        }
        
        // Set featured status
        $validated['featured'] = $request->has('featured');
        
        // Update published_at if status changed to published
        if ($validated['status'] === 'published' && $post->status !== 'published') {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('admin.supplier.posts.index')
            ->with('success', 'Supplier post updated successfully.');
    }

    /**
     * Remove the specified supplier post
     */
    public function destroy(SupplierPost $post)
    {
        // Delete image if exists
        if ($post->image && file_exists(public_path($post->image))) {
            unlink(public_path($post->image));
        }
        
        $post->delete();

        return redirect()->route('admin.supplier.posts.index')
            ->with('success', 'Supplier post deleted successfully.');
    }

    /**
     * Toggle featured status of a post
     */
    public function toggleFeatured(SupplierPost $post)
    {
        $post->update(['featured' => !$post->featured]);
        
        $status = $post->featured ? 'featured' : 'unfeatured';
        
        return redirect()->back()
            ->with('success', "Post {$status} successfully.");
    }

    /**
     * Toggle status of a post (draft/published)
     */
    public function toggleStatus(SupplierPost $post)
    {
        $newStatus = $post->status === 'published' ? 'draft' : 'published';
        $updateData = ['status' => $newStatus];
        
        if ($newStatus === 'published') {
            $updateData['published_at'] = now();
        }
        
        $post->update($updateData);
        
        return redirect()->back()
            ->with('success', "Post status changed to {$newStatus} successfully.");
    }
}
