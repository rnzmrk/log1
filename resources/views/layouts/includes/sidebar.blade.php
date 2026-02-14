{{-- Sidebar --}}
<div id="sidebar" data-sidebar class="sidebar fixed top-0 left-0 h-full bg-[#3b4fb8] w-64 md:w-72 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-gray-200 flex flex-col z-50">
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.jpg') }}" class="h-10 w-10 rounded-lg object-cover" alt="Logo">
            <span class="font-bold text-xl hidden md:inline text-white">IMARKET</span>
        </div>
        <button id="sidebarClose" class="md:hidden text-white hover:bg-[#7a8be8]/80 hover:bg-opacity-10 rounded-lg p-2 transition-colors">
            <i class='bx bx-x text-2xl'></i>
        </button>
    </div>

    <ul class="nav-list flex-1 mt-4 px-2 overflow-y-auto" style="max-height: 80vh;">
        <!-- Dashboard -->
        <li>
            <a href="/admin/dashboard" class="nav-link flex items-center gap-6 p-3 rounded-lg text-lg font-medium text-white hover:bg-[#5a6bd8] {{ request()->is('admin/dashboard') ? 'bg-[#7a8be8]/80' : '' }} mb-2 transition-colors" data-page="dashboard">
                <i class='bx bx-grid-alt text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Dashboard</span>
            </a>
        </li>

        <!-- Smart Warehousing -->
        <li class="has-dropdown" data-menu="warehousing">
            <button class="dropdown-toggle flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#5a6bd8] {{ request()->is('admin/warehousing*') ? 'bg-[#7a8be8]/80' : '' }} transition-colors">
                <i class='bx bx-package text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Smart Warehousing</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/warehousing/inbound-logistics" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/warehousing/inbound-logistics*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="inbound-logistics">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Inbound Logistics (Receiving & Putaway)</span>
                </a>
                <a href="/admin/warehousing/storage-inventory" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/warehousing/storage-inventory*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="storage-inventory">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Storage & Inventory Management</span>
                </a>
                <a href="/admin/warehousing/outbound-logistics" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/warehousing/outbound-logistics*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="outbound-logistics">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Outbound Logistics (Dispatch & Shipping)</span>
                </a>
                <a href="/admin/warehousing/returns-management" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/warehousing/returns-management*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="returns-management">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Returns Management (Reverse Logistics)</span>
                </a>
            </div>
        </li>

        <!-- Procurement -->
        <li class="has-dropdown" data-menu="procurement">
            <button class="dropdown-toggle flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#5a6bd8] {{ request()->is('admin/procurement*') ? 'bg-[#7a8be8]/80' : '' }} transition-colors">
                <i class='bx bx-purchase-tag-alt text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Procurement</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/procurement/request-supplies" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/procurement/request-supplies*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="request-supplies">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Request Supplies</span>
                </a>
                <a href="/admin/procurement/create-purchase-order" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/procurement/create-purchase-order*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="create-purchase-order">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Create Purchase Order</span>
                </a>
                <a href="/admin/procurement/vendors" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/procurement/vendors*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="vendors">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">List of Suppliers</span>
                </a>
                <!----<a href="/admin/procurement/create-contract-reports" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/procurement/create-contract-reports*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="create-contract-reports">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Create Contract and Reports</span>
                </a>--------->
            </div>
        </li> 

        <!-- Asset Lifecycle & Maintenance -->
        <li class="has-dropdown" data-menu="assetlifecycle">
            <button class="dropdown-toggle flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#5a6bd8] {{ request()->is('admin/assetlifecycle*') ? 'bg-[#7a8be8]/80' : '' }} transition-colors">
                <i class='bx bx-cube-alt text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Asset Lifecycle & Maintenance</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/assetlifecycle/request-asset" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/assetlifecycle/request-asset*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="request-asset">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Request Asset</span>
                </a>
                <a href="/admin/assetlifecycle/asset-management" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/assetlifecycle/asset-management*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="asset-management">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Asset Management</span>
                </a>
                <a href="/admin/assetlifecycle/asset-maintenance" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/assetlifecycle/asset-maintenance*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="asset-maintenance">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Asset Maintenance</span>
                </a>
                <a href="/admin/assetlifecycle/asset-disposal" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/assetlifecycle/asset-disposal*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="asset-disposal">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Asset Disposal</span>
                </a>
            </div>
        </li>

        <!-- Logistic Tracking -->
        <li class="has-dropdown" data-menu="logistictracking">
            <button class="dropdown-toggle flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#5a6bd8] {{ request()->is('admin/logistictracking*') ? 'bg-[#7a8be8]/80' : '' }} transition-colors">
                <i class='bx bx-map text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Logistic Tracking</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/logistictracking/request-vehicle" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin.logistictracking/request-vehicle*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="request-vehicle">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Request Vehicle</span>
                </a>
                <a href="/admin/logistictracking/list-vehicle" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/logistictracking/list-vehicle*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="list-vehicle">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Vehicle Monitoring</span>
                </a>
                <a href="/admin/logistictracking/maintenance" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/logistictracking/maintenance*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="maintenance">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Maintenance</span>
                </a>
                <a href="{{ route('project-planning.index') }}" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('project-planning*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="project-planning">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Project Planning & Request</span>
                </a>
                <!--------<a href="/admin/logistictracking/delivery-confirmation" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/logistictracking/delivery-confirmation*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="delivery-confirmation">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Delivery Confirmation</span>
                </a>
                
                <a href="/admin/logistictracking/reports" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/logistictracking/reports*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="reports">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Reports</span>
                </a>--------->
            </div>
        </li>

        <!-- Document Tracking -->
        <li class="has-dropdown" data-menu="documenttracking">
            <button class="dropdown-toggle flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#5a6bd8] {{ request()->is('admin/documenttracking*') ? 'bg-[#7a8be8]/80' : '' }} transition-colors">
                <i class='bx bx-folder text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Document Tracking</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/documenttracking/create-document-reports" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/documenttracking/create-document-reports*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="create-document-reports">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Create Document and Reports</span>
                </a>
                <a href="/admin/documenttracking/validation" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/documenttracking/validation*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="validation">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Validation</span>
                </a>
                <a href="/admin/documenttracking/verification" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/documenttracking/verification*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="verification">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Verification</span>
                </a>
                <a href="/admin/documenttracking/reports" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/documenttracking/reports*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="document-reports">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Reports</span>
                </a>
            </div>
        </li>

    <!-- Admin Settings -->
        <li class="has-dropdown" data-menu="adminsettings">
            <button class="dropdown-toggle flex items-center gap-4 w-full p-3 rounded-lg text-lg font-medium text-white hover:bg-[#5a6bd8] {{ request()->is('admin/adminsettings*') ? 'bg-[#7a8be8]/80' : '' }} transition-colors">
                <i class='bx bx-cog text-2xl text-white'></i>
                <span class="links_name inline text-[16px]">Admin Settings</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform transform duration-300'></i>
            </button>
            <div class="dropdown hidden flex-col pl-12 space-y-2 mt-2">
                <a href="/admin/adminsettings/users-roles" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/adminsettings/users-roles*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="users-roles">
                    <i class='bx bx-right-arrow-alt text-[12px]'></i>
                    <span class="text-sm">Users & Roles</span>
                </a>
                <a href="/admin/adminsettings/audit-logs" class="nav-link flex items-center gap-3 p-2 rounded-lg text-gray-100 hover:bg-[#5a6bd8] {{ request()->is('admin/adminsettings/audit-logs*') ? 'bg-white text-[#3b4fb8]' : '' }} transition-colors" data-page="audit-logs">
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
                        <button type="submit" class="text-white hover:bg-[#7a8be8]/80 hover:bg-opacity-10 rounded-lg p-2 transition-colors" title="Logout">
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
