/**
 * Sidebar Navigation System
 * Handles dropdown toggles, active states, and mobile responsiveness
 */
class SidebarNavigation {
    constructor() {
        this.init();
    }

    init() {
        this.setupDropdownToggles();
        this.setupActiveStates();
        this.setupMobileToggle();
        this.setupOverlay();
        this.setupClickHandlers();
        this.expandActiveDropdown();
    }

    /**
     * Setup click handlers for navigation links
     */
    setupClickHandlers() {
        document.addEventListener('click', (e) => {
            const navLink = e.target.closest('.nav-link');
            if (navLink && navLink.getAttribute('href')) {
                // Don't interfere with normal navigation - just update visual states
                setTimeout(() => {
                    this.setupActiveStates();
                }, 100);
            }
        });
    }

    /**
     * Setup dropdown toggle functionality
     */
    setupDropdownToggles() {
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('.dropdown-toggle');
            if (toggle) {
                e.preventDefault();
                const dropdown = toggle.closest('.has-dropdown');
                const dropdownContent = dropdown.querySelector('.dropdown');
                const chevron = toggle.querySelector('.bx-chevron-down');
                
                // Close other dropdowns
                document.querySelectorAll('.has-dropdown').forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        const otherContent = otherDropdown.querySelector('.dropdown');
                        const otherChevron = otherDropdown.querySelector('.bx-chevron-down');
                        otherContent.classList.add('hidden');
                        otherChevron.style.transform = 'rotate(0deg)';
                    }
                });
                
                // Toggle current dropdown
                dropdownContent.classList.toggle('hidden');
                if (dropdownContent.classList.contains('hidden')) {
                    chevron.style.transform = 'rotate(0deg)';
                } else {
                    chevron.style.transform = 'rotate(180deg)';
                }
            }
        });
    }

    /**
     * Setup active states for navigation links
     */
    setupActiveStates() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        // First, clear all active states
        navLinks.forEach(link => {
            link.classList.remove('bg-white', 'text-[#3b4fb8]');
            link.classList.add('text-gray-100');
        });
        
        // Clear all parent dropdown highlights
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.classList.remove('bg-[#7a8be8]/80');
        });
        
        // Then set active states for current page
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && href !== '/') {
                // Exact match first
                if (currentPath === href) {
                    link.classList.add('bg-white', 'text-[#3b4fb8]');
                    link.classList.remove('text-gray-100');
                    this.highlightParentDropdown(link);
                }
                // Then check if current path starts with href (for parent pages)
                else if (currentPath.startsWith(href + '/') || currentPath.startsWith(href)) {
                    // Make sure we don't highlight parent when child is active
                    const hasMoreSpecificMatch = Array.from(navLinks).some(otherLink => {
                        const otherHref = otherLink.getAttribute('href');
                        return otherHref && otherHref !== href && 
                               otherHref !== '/' && 
                               currentPath.startsWith(otherHref) && 
                               otherHref.startsWith(href);
                    });
                    
                    if (!hasMoreSpecificMatch) {
                        link.classList.add('bg-white', 'text-[#3b4fb8]');
                        link.classList.remove('text-gray-100');
                        this.highlightParentDropdown(link);
                    }
                }
            }
        });
    }

    /**
     * Highlight parent dropdown
     */
    highlightParentDropdown(link) {
        const parentDropdown = link.closest('.has-dropdown');
        if (parentDropdown) {
            const toggle = parentDropdown.querySelector('.dropdown-toggle');
            const chevron = toggle.querySelector('.bx-chevron-down');
            const dropdownContent = parentDropdown.querySelector('.dropdown');
            
            // Add light purple background to parent
            toggle.classList.add('bg-[#7a8be8]/80');
            
            dropdownContent.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
        }
    }

    /**
     * Setup mobile menu toggle
     */
    setupMobileToggle() {
        const mobileToggle = document.querySelector('[data-mobile-toggle]');
        const sidebar = document.querySelector('[data-sidebar]');
        const mainContent = document.querySelector('[data-main-content]');
        
        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                mainContent.classList.toggle('ml-0');
                mainContent.classList.toggle('ml-72');
            });
        }
    }

    /**
     * Setup overlay for mobile
     */
    setupOverlay() {
        const overlay = document.getElementById('overlay');
        const sidebar = document.querySelector('[data-sidebar]');
        const mainContent = document.querySelector('[data-main-content]');
        
        if (overlay && sidebar) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.add('ml-0');
                mainContent.classList.remove('ml-72');
                overlay.classList.add('hidden');
            });
        }
    }

    /**
     * Expand dropdown containing active page
     */
    expandActiveDropdown() {
        const activeLink = document.querySelector('.nav-link.bg-white');
        if (activeLink) {
            this.highlightParentDropdown(activeLink);
        }
    }
}

// Initialize the sidebar navigation when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.sidebarNavigation = new SidebarNavigation();
});

// Re-initialize on page changes (for SPA-like behavior)
window.addEventListener('popstate', function() {
    if (window.sidebarNavigation) {
        window.sidebarNavigation.setupActiveStates();
    }
});
