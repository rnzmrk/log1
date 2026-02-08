{{-- Sidebar --}}
<div id="sidebar" class="sidebar fixed top-0 left-0 h-full bg-[#2c3c8c] w-64 md:w-72 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-gray-200 flex flex-col z-50">
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.jpg') }}" class="h-10 w-10 rounded-lg object-cover" alt="Logo">
            <span class="font-bold text-xl hidden md:inline text-white">IMARKET</span>
        </div>
        <button id="sidebarClose" class="md:hidden text-white hover:bg-white hover:bg-opacity-10 rounded-lg p-2 transition-colors">
            <i class='bx bx-x text-2xl'></i>
        </button>
    </div>

    <ul class="nav-list flex-1 mt-4 px-2 overflow-y-auto" style="max-height: 80vh;">
        <!-- Dashboard -->
        <li>
            <a href="/admin/dashboard" class="flex items-center gap-6 p-3 rounded-lg text-lg font-medium text-white hover:bg-[#4353a2] bg-[#5c6cb8] mb-2 transition-colors">
                <i class='bx bx-grid-alt text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Dashboard</span>
            </a>
        </li>

        <!-- Smart Warehousing -->
        <li class="has-dropdown">
            <button class="flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#4353a2] transition-colors">
                <i class='bx bx-package text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Smart Warehousing</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/warehousing/inbound-logistics" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Inbound Logistics (Receiving & Putaway)</span>
                </a>
                <a href="/admin/warehousing/storage-inventory" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Storage & Inventory Management</span>
                </a>
                <a href="/admin/warehousing/outbound-logistics" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Outbound Logistics (Dispatch & Shipping)</span>
                </a>
                <a href="/admin/warehousing/returns-management" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Returns Management (Reverse Logistics)</span>
                </a>
            </div>
        </li>

        <!-- Procurement -->
        <li class="has-dropdown">
            <button class="flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#4353a2] transition-colors">
                <i class='bx bx-purchase-tag-alt text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Procurement</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/procurement/request-supplies" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Request Supplies</span>
                </a>
                <a href="/admin/procurement/create-purchase-order" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Create Purchase Order</span>
                </a>
                <a href="/admin/procurement/vendors" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">List of Vendors</span>
                </a>
                <a href="/admin/procurement/create-contract-reports" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Create Contract and Reports</span>
                </a>
            </div>
        </li>

        <!-- Asset Lifecycle & Maintenance -->
        <li class="has-dropdown">
            <button class="flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#4353a2] transition-colors">
                <i class='bx bx-cube-alt text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Asset Lifecycle & Maintenance</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/assetlifecycle/request-asset" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Request Asset</span>
                </a>
                <a href="/admin/assetlifecycle/asset-management" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Asset Management</span>
                </a>
                <a href="{{ route('admin.assetlifecycle.asset-maintenance') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Asset Maintenance</span>
                </a>
            </div>
        </li>

        <!-- Logistic Tracking -->
        <li class="has-dropdown">
            <button class="flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#4353a2] transition-colors">
                <i class='bx bx-map text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Logistic Tracking</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="{{ route('admin.logistictracking.request-vehicle') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Request Vehicle</span>
                </a>
                <a href="/admin/logistictracking/delivery-confirmation" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Delivery Confirmation</span>
                </a>
                <a href="/admin/logistictracking/project-planning-request" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Project Planning & Request</span>
                </a>
                <a href="/admin/logistictracking/reports" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Reports</span>
                </a>
            </div>
        </li>

        <!-- Document Tracking -->
        <li class="has-dropdown">
            <button class="flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#4353a2] transition-colors">
                <i class='bx bx-folder text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Document Tracking</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/documenttracking/document-request" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Document Request</span>
                </a>
                <a href="/admin/documenttracking/list-document-request" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">List of Document Request</span>
                </a>
                <a href="/admin/documenttracking/upload-document-tracking" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Upload Document and Tracking</span>
                </a>
                <a href="/admin/documenttracking/reports" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Reports</span>
                </a>
            </div>
        </li>

    <!-- Admin Settings -->
        <li class="has-dropdown border-t border-gray-600 pt-4 mt-4">
            <button class="flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#4353a2] transition-colors">
                <i class='bx bx-cog text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Admin Settings</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/adminsettings/users-roles" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Users & Roles</span>
                </a>
                <a href="/admin/adminsettings/audit-logs" class="flex items-center gap-3 p-2 rounded-lg text-gray-200 hover:bg-[#4353a2] transition-colors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Audit Logs</span>
                </a>
            </div>
        </li>

    </ul>

    <div class="p-4 border-t border-gray-200 flex items-center gap-2">
        <!-- User Profile with dynamic data -->
        @auth
            @php
                $user = Auth::user();
                $initials = strtoupper(substr($user->name, 0, 2));
                $fullName = $user->name;
                $role = 'Administrator'; // You can make this dynamic if you have roles
            @endphp
            
            <!-- Initials Circle with link -->
            <a href="{{ route('admin.dashboard') }}" class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg hover:opacity-80 transition">
                {{ $initials }}
            </a>

            <div class="hidden md:flex flex-col flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <span class="font-semibold text-lg text-white truncate">
                        {{ $fullName }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Are you sure you want to logout?')" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:bg-white hover:bg-opacity-10 rounded-lg p-2 transition-colors" title="Logout">
                            <i class='bx bx-log-out text-xl'></i>
                        </button>
                    </form>
                </div>
                <span class="text-sm text-gray-300">
                    {{ $role }}
                </span>
            </div>
        @else
            <!-- Fallback for non-authenticated users -->
            <div class="h-12 w-12 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold text-lg">
                GU
            </div>

            <div class="hidden md:flex flex-col">
                <span class="font-semibold text-lg text-white">
                    Guest User
                </span>
                <span class="text-sm text-gray-300">
                    Not Logged In
                </span>
            </div>
        @endauth
    </div>
</div>

{{-- Sidebar Styles --}}
@once
@push('styles')
<style>
    /* Scrollbar hidden for sidebar */
    .sidebar::-webkit-scrollbar { display: none; }

    /* Dropdown hidden by default */
    .has-dropdown > .dropdown { display: none; }
    .has-dropdown.active > .dropdown { display: flex; flex-direction: column; }

    /* Smooth transitions */
    .sidebar, .main-content { transition: all 0.3s ease; }

    /* Hide scrollbar for all browsers */
    .nav-list::-webkit-scrollbar {
      display: none; /* Chrome, Safari, Opera */
    }
    .nav-list {
      -ms-overflow-style: none;  /* IE and Edge */
      scrollbar-width: none;     /* Firefox */
    }
</style>
@endpush
@endonce

{{-- Sidebar Scripts --}}
@once
@push('scripts')
<script>
// Sidebar open/close
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('main-content');
const sidebarOpenBtn = document.getElementById('sidebarOpen');
const sidebarCloseBtn = document.getElementById('sidebarClose');
const overlay = document.getElementById('overlay');

sidebarOpenBtn?.addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
    overlay.style.display = 'block';
});

sidebarCloseBtn?.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    overlay.style.display = 'none';
});

overlay?.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    overlay.style.display = 'none';
});

// Dropdowns
document.querySelectorAll('.has-dropdown > button').forEach(btn => {
    btn.addEventListener('click', () => {
        const parentLi = btn.parentElement;
        const dropdown = parentLi.querySelector('.dropdown');
        const icon = btn.querySelector('.bx-chevron-down');

        const isOpen = parentLi.classList.contains('active');

        // Close all other open dropdowns
        document.querySelectorAll('.has-dropdown.active').forEach(otherLi => {
            if (otherLi !== parentLi) {
                otherLi.classList.remove('active');
                otherLi.querySelector('.dropdown').classList.add('hidden');
                otherLi.querySelector('.dropdown').classList.remove('flex');
                otherLi.querySelector('.bx-chevron-down')?.classList.remove('rotate-180');
            }
        });

        // Toggle current dropdown
        if (isOpen) {
            parentLi.classList.remove('active');
            dropdown.classList.add('hidden');
            dropdown.classList.remove('flex');
            icon?.classList.remove('rotate-180');
        } else {
            parentLi.classList.add('active');
            dropdown.classList.remove('hidden');
            dropdown.classList.add('flex');
            icon?.classList.add('rotate-180');
        }
    });
});

// Close dropdowns when sidebar is closed on mobile
sidebarCloseBtn?.addEventListener('click', () => {
    document.querySelectorAll('.has-dropdown.active').forEach(li => {
        li.classList.remove('active');
        li.querySelector('.dropdown').classList.add('hidden');
        li.querySelector('.dropdown').classList.remove('flex');
        li.querySelector('.bx-chevron-down')?.classList.remove('rotate-180');
    });
});

// Close dropdowns when overlay is clicked
overlay?.addEventListener('click', () => {
    document.querySelectorAll('.has-dropdown.active').forEach(li => {
        li.classList.remove('active');
        li.querySelector('.dropdown').classList.add('hidden');
        li.querySelector('.dropdown').classList.remove('flex');
        li.querySelector('.bx-chevron-down')?.classList.remove('rotate-180');
    });
});

// Handle window resize
window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) {
        // On desktop, close mobile sidebar
        overlay.style.display = 'none';
    }
});
</script>
@endpush
@endonce
