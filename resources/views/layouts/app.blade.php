<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.jpg') }}">
    <title>IMARKET - Admin Dashboard</title>

    {{-- styles|scripts --}}
    @stack('styles')
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS for Sidebar -->
    <style>
        @media (min-width: 768px) {
            #sidebar {
                transform: translateX(0) !important;
            }
        }
    </style>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    {{-- Sidebar --}}
    @include('layouts.includes.sidebar')

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col ml-0 md:ml-72 transition-all duration-300" data-main-content>
        {{-- Header --}}
        @include('layouts.includes.header')

        {{-- Page Content --}}
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    {{-- Overlay for mobile --}}
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
</div>

    {{-- Scripts --}}
    @stack('scripts')
    
    {{-- Sidebar Navigation System --}}
    @include('includes.sidebar-js')
    
    {{-- Admin Notifications System --}}
    @if(request()->is('admin/*'))
        @include('includes.admin-notifications')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
</body>
</html>