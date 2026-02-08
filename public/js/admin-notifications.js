/**
 * Admin Notification System
 * Handles all popup messages for CRUD operations and approval transactions
 */
class AdminNotificationSystem {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormSubmissions();
        this.setupDeleteConfirmations();
        this.setupApprovalActions();
        this.setupStatusUpdates();
        this.displayFlashMessages();
    }

    /**
     * Setup form submission notifications
     */
    setupFormSubmissions() {
        // Handle all form submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (submitBtn) {
                const action = this.getFormAction(form);
                this.showProcessingMessage(action);
                
                // Store original button content
                const originalContent = submitBtn.innerHTML;
                
                // Update button to show processing state
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <i class='bx bx-loader-alt bx-spin text-lg'></i>
                    <span>Processing...</span>
                `;
                
                // Restore button after 5 seconds (fallback)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }, 5000);
            }
        });
    }

    /**
     * Setup delete confirmation dialogs
     */
    setupDeleteConfirmations() {
        // Handle delete buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.delete-btn, [data-delete]')) {
                e.preventDefault();
                const btn = e.target.closest('.delete-btn, [data-delete]');
                const itemName = btn.dataset.item || 'this item';
                const deleteUrl = btn.dataset.url || btn.href;
                
                this.showDeleteConfirmation(itemName, deleteUrl);
            }
        });
    }

    /**
     * Setup approval action notifications
     */
    setupApprovalActions() {
        // Handle approval buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.approve-btn, [data-approve]')) {
                e.preventDefault();
                const btn = e.target.closest('.approve-btn, [data-approve]');
                const itemName = btn.dataset.item || 'this request';
                const approveUrl = btn.dataset.url || btn.href;
                
                this.showApprovalConfirmation(itemName, approveUrl);
            }
        });

        // Handle rejection buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.reject-btn, [data-reject]')) {
                e.preventDefault();
                const btn = e.target.closest('.reject-btn, [data-reject]');
                const itemName = btn.dataset.item || 'this request';
                const rejectUrl = btn.dataset.url || btn.href;
                
                this.showRejectionConfirmation(itemName, rejectUrl);
            }
        });
    }

    /**
     * Setup status update notifications
     */
    setupStatusUpdates() {
        // Handle status change dropdowns
        document.addEventListener('change', (e) => {
            if (e.target.matches('.status-select, [data-status-update]')) {
                const select = e.target;
                const newStatus = select.value;
                const itemName = select.dataset.item || 'this item';
                
                if (newStatus && newStatus !== select.dataset.originalStatus) {
                    this.showStatusUpdateConfirmation(itemName, newStatus, () => {
                        // Submit the status update
                        const form = select.closest('form');
                        if (form) {
                            form.submit();
                        }
                    });
                }
            }
        });
    }

    /**
     * Display flash messages from Laravel
     */
    displayFlashMessages() {
        // Check for success messages
        const successAlert = document.querySelector('.alert-success, [data-success]');
        if (successAlert) {
            const message = successAlert.textContent || successAlert.dataset.success;
            this.showSuccessNotification(message.trim());
            successAlert.remove();
        }

        // Check for error messages
        const errorAlert = document.querySelector('.alert-danger, [data-error]');
        if (errorAlert) {
            const message = errorAlert.textContent || errorAlert.dataset.error;
            this.showErrorNotification(message.trim());
            errorAlert.remove();
        }

        // Check for warning messages
        const warningAlert = document.querySelector('.alert-warning, [data-warning]');
        if (warningAlert) {
            const message = warningAlert.textContent || warningAlert.dataset.warning;
            this.showWarningNotification(message.trim());
            warningAlert.remove();
        }

        // Check for info messages
        const infoAlert = document.querySelector('.alert-info, [data-info]');
        if (infoAlert) {
            const message = infoAlert.textContent || infoAlert.dataset.info;
            this.showInfoNotification(message.trim());
            infoAlert.remove();
        }
    }

    /**
     * Get form action type
     */
    getFormAction(form) {
        const method = form.querySelector('input[name="_method"]')?.value || 'POST';
        const action = form.action.toLowerCase();
        
        if (action.includes('create') || action.includes('store')) {
            return 'Creating';
        } else if (action.includes('edit') || action.includes('update')) {
            return 'Updating';
        } else if (method === 'DELETE') {
            return 'Deleting';
        } else {
            return 'Processing';
        }
    }

    /**
     * Show processing message
     */
    showProcessingMessage(action) {
        this.showNotification({
            type: 'info',
            title: `${action}...`,
            message: `Please wait while we ${action.toLowerCase()} your request.`,
            duration: 3000,
            icon: 'bx-loader-alt bx-spin'
        });
    }

    /**
     * Show delete confirmation dialog
     */
    showDeleteConfirmation(itemName, deleteUrl) {
        this.showModal({
            type: 'warning',
            title: 'Confirm Delete',
            message: `Are you sure you want to delete "${itemName}"? This action cannot be undone.`,
            confirmText: 'Delete',
            confirmClass: 'bg-red-600 hover:bg-red-700',
            onConfirm: () => {
                this.showNotification({
                    type: 'info',
                    title: 'Deleting...',
                    message: `Deleting "${itemName}"...`,
                    duration: 2000
                });
                
                // Submit delete request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.innerHTML = '<input name="_method" value="DELETE" type="hidden">' +
                               '<input name="_token" value="' + document.querySelector('meta[name="csrf-token"]').getAttribute('content') + '" type="hidden">';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    /**
     * Show approval confirmation dialog
     */
    showApprovalConfirmation(itemName, approveUrl) {
        this.showModal({
            type: 'success',
            title: 'Confirm Approval',
            message: `Are you sure you want to approve "${itemName}"?`,
            confirmText: 'Approve',
            confirmClass: 'bg-green-600 hover:bg-green-700',
            onConfirm: () => {
                this.showNotification({
                    type: 'info',
                    title: 'Approving...',
                    message: `Approving "${itemName}"...`,
                    duration: 2000
                });
                
                // Submit approval request
                window.location.href = approveUrl;
            }
        });
    }

    /**
     * Show rejection confirmation dialog
     */
    showRejectionConfirmation(itemName, rejectUrl) {
        this.showModal({
            type: 'warning',
            title: 'Confirm Rejection',
            message: `Are you sure you want to reject "${itemName}"? Please provide a reason.`,
            confirmText: 'Reject',
            confirmClass: 'bg-red-600 hover:bg-red-700',
            showReasonInput: true,
            onConfirm: (reason) => {
                this.showNotification({
                    type: 'info',
                    title: 'Rejecting...',
                    message: `Rejecting "${itemName}"...`,
                    duration: 2000
                });
                
                // Submit rejection request with reason
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = rejectUrl;
                form.innerHTML = '<input name="_method" value="POST" type="hidden">' +
                               '<input name="_token" value="' + document.querySelector('meta[name="csrf-token"]').getAttribute('content') + '" type="hidden">' +
                               '<input name="rejection_reason" value="' + reason + '" type="hidden">';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    /**
     * Show status update confirmation dialog
     */
    showStatusUpdateConfirmation(itemName, newStatus, onConfirm) {
        this.showModal({
            type: 'info',
            title: 'Confirm Status Change',
            message: `Are you sure you want to change the status of "${itemName}" to "${newStatus}"?`,
            confirmText: 'Update Status',
            confirmClass: 'bg-blue-600 hover:bg-blue-700',
            onConfirm: onConfirm
        });
    }

    /**
     * Show notification toast
     */
    showNotification(options) {
        const {
            type = 'info',
            title = 'Notification',
            message = '',
            duration = 4000,
            icon = this.getIconForType(type)
        } = options;

        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 max-w-sm w-full bg-white rounded-lg shadow-lg border-l-4 ${this.getBorderClassForType(type)} z-50 transform transition-all duration-300 translate-x-full`;
        
        notification.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class='bx ${icon} text-2xl ${this.getTextClassForType(type)}'></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-gray-900">${title}</h3>
                        <p class="mt-1 text-sm text-gray-600">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-lg'></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 100);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }
    }

    /**
     * Show modal dialog
     */
    showModal(options) {
        const {
            type = 'info',
            title = 'Confirm Action',
            message = '',
            confirmText = 'Confirm',
            cancelText = 'Cancel',
            confirmClass = 'bg-blue-600 hover:bg-blue-700',
            showReasonInput = false,
            onConfirm = () => {},
            onCancel = () => {}
        } = options;

        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
        
        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center mb-4">
                        <i class='bx ${this.getIconForType(type)} text-2xl ${this.getTextClassForType(type)} mr-3'></i>
                        <h3 class="text-lg font-medium text-gray-900">${title}</h3>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">${message}</p>
                        ${showReasonInput ? `
                            <textarea id="reasonInput" class="mt-3 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                      placeholder="Please provide a reason..." rows="3"></textarea>
                        ` : ''}
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" class="cancel-btn bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                            ${cancelText}
                        </button>
                        <button type="button" class="confirm-btn ${confirmClass} text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Handle button clicks
        modal.querySelector('.confirm-btn').addEventListener('click', () => {
            const reason = showReasonInput ? modal.querySelector('#reasonInput').value : '';
            onConfirm(reason);
            modal.remove();
        });

        modal.querySelector('.cancel-btn').addEventListener('click', () => {
            onCancel();
            modal.remove();
        });

        // Close on background click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    /**
     * Show success notification
     */
    showSuccessNotification(message) {
        this.showNotification({
            type: 'success',
            title: 'Success!',
            message: message
        });
    }

    /**
     * Show error notification
     */
    showErrorNotification(message) {
        this.showNotification({
            type: 'error',
            title: 'Error!',
            message: message,
            duration: 6000
        });
    }

    /**
     * Show warning notification
     */
    showWarningNotification(message) {
        this.showNotification({
            type: 'warning',
            title: 'Warning!',
            message: message
        });
    }

    /**
     * Show info notification
     */
    showInfoNotification(message) {
        this.showNotification({
            type: 'info',
            title: 'Information',
            message: message
        });
    }

    /**
     * Get icon for notification type
     */
    getIconForType(type) {
        const icons = {
            success: 'bx-check-circle',
            error: 'bx-x-circle',
            warning: 'bx-error',
            info: 'bx-info-circle'
        };
        return icons[type] || 'bx-info-circle';
    }

    /**
     * Get text color class for type
     */
    getTextClassForType(type) {
        const classes = {
            success: 'text-green-600',
            error: 'text-red-600',
            warning: 'text-yellow-600',
            info: 'text-blue-600'
        };
        return classes[type] || 'text-blue-600';
    }

    /**
     * Get border color class for type
     */
    getBorderClassForType(type) {
        const classes = {
            success: 'border-green-500',
            error: 'border-red-500',
            warning: 'border-yellow-500',
            info: 'border-blue-500'
        };
        return classes[type] || 'border-blue-500';
    }
}

// Initialize the notification system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.adminNotifications = new AdminNotificationSystem();
});

// Global functions for manual calls
window.showSuccess = function(message) {
    if (window.adminNotifications) {
        window.adminNotifications.showSuccessNotification(message);
    }
};

window.showError = function(message) {
    if (window.adminNotifications) {
        window.adminNotifications.showErrorNotification(message);
    }
};

window.showWarning = function(message) {
    if (window.adminNotifications) {
        window.adminNotifications.showWarningNotification(message);
    }
};

window.showInfo = function(message) {
    if (window.adminNotifications) {
        window.adminNotifications.showInfoNotification(message);
    }
};
