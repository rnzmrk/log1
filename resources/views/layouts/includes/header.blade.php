{{-- Header --}}
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between w-full">
            {{-- Mobile menu button --}}
            <button id="sidebarOpen" data-mobile-toggle class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                <i class='bx bx-menu text-2xl'></i>
            </button>

            <div class="flex-1"></div>

            {{-- Right side icons --}}
            <div class="flex items-center space-x-4 ml-auto">
                {{-- Notifications --}}
                <div class="relative">
                    <button id="notificationBtn" class="text-gray-600 hover:text-gray-900 focus:outline-none relative">
                        <i class='bx bx-bell text-2xl'></i>
                    </button>
                    
                    {{-- Notification Dropdown --}}
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <!-- Notifications will be dynamically loaded here -->
                            <div class="px-4 py-8 text-center text-gray-500">
                                <i class='bx bx-bell text-3xl mb-2'></i>
                                <p class="text-sm">No new notifications</p>
                            </div>
                        </div>
                        <div class="px-4 py-3 border-t border-gray-200 text-center">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all notifications</a>
                        </div>
                    </div>
                </div>

                {{-- User profile --}}
                <div class="relative">
                    <button id="profileBtn" class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 focus:outline-none">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">JD</span>
                        </div>
                        <i class='bx bx-chevron-down text-xl'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
// Notification dropdown
const notificationBtn = document.getElementById('notificationBtn');
const notificationDropdown = document.getElementById('notificationDropdown');

notificationBtn?.addEventListener('click', function(e) {
    e.stopPropagation();
    notificationDropdown.classList.toggle('hidden');
});

// Close notification dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
        notificationDropdown.classList.add('hidden');
    }
});
</script>
@endpush