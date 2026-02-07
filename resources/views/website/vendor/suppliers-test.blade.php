@extends('website.layouts.app')

@section('title', 'Suppliers - Find Trusted Vendors')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Suppliers Directory</h1>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Total Suppliers: {{ $suppliers->count() }}</p>
            <p class="text-gray-600">Featured Posts: {{ $featuredPosts->count() }}</p>
            <p class="text-gray-600">Categories: {{ $categories->count() }}</p>
        </div>
    </div>
</div>
@endsection
