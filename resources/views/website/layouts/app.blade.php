<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Supplier Directory')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Custom Styles -->
    <style>
        .avatar-initials {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
        }
        
        .hover-scale {
            transition: transform 0.2s;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('website.home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2">
                        <span class="text-xl font-bold text-gray-900">Supplier Directory</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('website.home') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Home
                    </a>
                    <a href="{{ route('website.suppliers.register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class='bx bx-plus mr-1'></i>
                        Register
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none" onclick="toggleMobileMenu()">
                        <i class='bx bx-menu text-2xl'></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('website.home') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">
                    Home
                </a>
                <a href="{{ route('website.suppliers.register') }}" class="block px-3 py-2 bg-blue-600 text-white font-medium rounded-lg">
                    <i class='bx bx-plus mr-1'></i>
                    Register
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2">
                        <span class="text-xl font-bold">Supplier Directory</span>
                    </div>
                    <p class="text-gray-400">
                        Connecting businesses with trusted suppliers across all industries.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('website.home') }}" class="text-gray-400 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('website.suppliers.register') }}" class="text-gray-400 hover:text-white transition-colors">
                                Register as Supplier
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Popular Categories</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('website.suppliers.index', ['category' => 'Computer Supplier']) }}" class="text-gray-400 hover:text-white transition-colors">
                                Computer Suppliers
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('website.suppliers.index', ['category' => 'Office Supplies Supplier']) }}" class="text-gray-400 hover:text-white transition-colors">
                                Office Supplies
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('website.suppliers.index', ['category' => 'Cellphone Supplier']) }}" class="text-gray-400 hover:text-white transition-colors">
                                Cellphone Suppliers
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class='bx bx-envelope mr-2'></i>
                            info@supplierdirectory.com
                        </li>
                        <li class="flex items-center">
                            <i class='bx bx-phone mr-2'></i>
                            +1-555-0123
                        </li>
                        <li class="flex items-center">
                            <i class='bx bx-map-pin mr-2'></i>
                            Business City, State 12345
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Supplier Directory. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Success Messages -->
    @if(session('success'))
    <div class="fixed top-20 right-4 bg-green-50 border border-green-200 rounded-lg p-4 max-w-sm z-50" id="successMessage">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class='bx bx-check-circle text-green-400 text-xl'></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if(session('error'))
    <div class="fixed top-20 right-4 bg-red-50 border border-red-200 rounded-lg p-4 max-w-sm z-50" id="errorMessage">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class='bx bx-error-circle text-red-400 text-xl'></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Auto-hide messages after 5 seconds
        setTimeout(function() {
            const successMsg = document.getElementById('successMessage');
            const errorMsg = document.getElementById('errorMessage');
            
            if (successMsg) {
                successMsg.style.display = 'none';
            }
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
        }, 5000);

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const menuButton = event.target.closest('button');
            
            if (!menu.contains(event.target) && !menuButton) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
