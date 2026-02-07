<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display the website home page with featured suppliers
     */
    public function home()
    {
        // Get featured suppliers and posts for homepage
        $featuredSuppliers = Supplier::active()->featured()->take(6)->get();
        $featuredPosts = SupplierPost::published()->featured()->take(3)->with('supplier')->get();
        $categories = Supplier::active()->distinct('category')->pluck('category')->filter()->sort()->values();
        
        return view('website.home', compact('featuredSuppliers', 'featuredPosts', 'categories'));
    }

    /**
     * Display the suppliers directory page
     */
    public function index(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search');
        $category = $request->get('category');
        
        // Build query
        $query = Supplier::active();
        
        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if ($category) {
            $query->where('category', $category);
        }
        
        // Get suppliers with pagination
        $suppliers = $query->orderBy('name')->paginate(12);
        
        // Get all categories for filter dropdown
        $categories = Supplier::active()->distinct('category')->pluck('category')->filter()->sort()->values();
        
        // Get featured posts for sidebar
        $featuredPosts = SupplierPost::published()->featured()->take(3)->with('supplier')->get();
        
        return view('website.vendor.suppliers-test', compact('suppliers', 'categories', 'featuredPosts', 'search', 'category'));
    }

    /**
     * Display the supplier registration form
     */
    public function register()
    {
        return view('website.vendor.register');
    }

    /**
     * Handle supplier registration submission
     */
    public function store(Request $request)
    {
        // Validate the registration data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'website' => 'nullable|url|max:255',
        ], [
            'name.required' => 'Company name is required.',
            'contact_person.required' => 'Contact person name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'Phone number is required.',
            'address.required' => 'Address is required.',
            'city.required' => 'City is required.',
            'state.required' => 'State is required.',
            'postal_code.required' => 'Postal code is required.',
            'country.required' => 'Country is required.',
            'category.required' => 'Please select a supplier category.',
        ]);

        // Create the supplier
        $supplier = Supplier::create($validated);

        // Redirect with success message
        return redirect()->route('website.suppliers.register')
            ->with('success', 'Registration submitted successfully! Your application will be reviewed by our team. You will receive an email once your registration is approved.');
    }

    /**
     * Display a specific supplier's details
     */
    public function show(Supplier $supplier)
    {
        // Only show active suppliers
        if ($supplier->status !== 'Active') {
            abort(404);
        }

        // Get supplier's public posts
        $posts = $supplier->publicPosts()->published()->latest()->get();
        
        // Get related suppliers in same category
        $relatedSuppliers = Supplier::active()
            ->where('category', $supplier->category)
            ->where('id', '!=', $supplier->id)
            ->take(4)
            ->get();

        return view('website.vendor.supplier-detail', compact('supplier', 'posts', 'relatedSuppliers'));
    }

    /**
     * Display a specific supplier post
     */
    public function post(SupplierPost $post)
    {
        // Only show published posts
        if ($post->status !== 'published') {
            abort(404);
        }

        // Increment view count
        $post->increment('views');

        // Get related posts
        $relatedPosts = SupplierPost::published()
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->with('supplier')
            ->take(3)
            ->get();

        return view('website.vendor.post-detail', compact('post', 'relatedPosts'));
    }
}
