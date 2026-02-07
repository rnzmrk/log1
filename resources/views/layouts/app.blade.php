<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Admin Dashboard</title>

    {{-- styles|scripts --}}
    @stack('styles')
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    {{-- Sidebar --}}
    @include('layouts.includes.sidebar')

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col ml-0 md:ml-72 transition-all duration-300">
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
    
</body>
</html>