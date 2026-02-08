<?php

use App\Http\Controllers\SmartWarehousing\InboundLogisticController;
use App\Http\Controllers\SmartWarehousing\InventoryController;
use App\Http\Controllers\SmartWarehousing\OutboundLogisticController;
use App\Http\Controllers\SmartWarehousing\ReturnRefundController;
use App\Http\Controllers\Procurement\SupplyRequestController;
use App\Http\Controllers\Procurement\PurchaseOrderController;
use App\Http\Controllers\Procurement\ContractController;
use App\Http\Controllers\AssetLifecycle\AssetRequestController;
use App\Http\Controllers\AssetLifecycle\AssetManagementController;
use App\Http\Controllers\AssetLifecycle\AssetMaintenanceController;
use App\Http\Controllers\LogisticsTracking\DeliveryConfirmationController;
use App\Http\Controllers\LogisticsTracking\ProjectPlanningController;
use App\Http\Controllers\LogisticsTracking\LogisticsReportController;
use App\Http\Controllers\DocumentTracking\DocumentRequestController;
use App\Http\Controllers\DocumentTracking\UploadedDocumentController;
use App\Http\Controllers\DocumentTracking\DocumentReportController;
use App\Http\Controllers\AdminSettings\UserController;
use App\Http\Controllers\AdminSettings\AuditLogController;
use App\Http\Controllers\Procurement\VendorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InventoryController as NewInventoryController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\LogisticsController;


// Authentication Routes
Route::get('/', [AuthController::class, 'showAuthForm'])->name('auth');
Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/resend-otp', [AuthController::class, 'resendOTP'])->name('resend.otp');

// Protected Routes - Require Authentication
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/warehousing/inbound-logistics', [InboundLogisticController::class, 'index'])->name('admin.warehousing.inbound-logistics');
Route::resource('inbound-logistics', InboundLogisticController::class)->except(['destroy'])->names([
    'index' => 'inbound-logistics.index',
    'create' => 'inbound-logistics.create',
    'store' => 'inbound-logistics.store',
    'show' => 'inbound-logistics.show',
    'edit' => 'inbound-logistics.edit',
    'update' => 'inbound-logistics.update',
]);

// Inbound Logistics Actions
Route::post('/inbound-logistics/{inboundLogistic}/accept', [InboundLogisticController::class, 'acceptShipment'])->name('inbound-logistics.accept');
Route::post('/inbound-logistics/{inboundLogistic}/reject', [InboundLogisticController::class, 'rejectShipment'])->name('inbound-logistics.reject');
Route::get('/inbound-logistics/export', [InboundLogisticController::class, 'export'])->name('inbound-logistics.export');
Route::post('/inbound-logistics/bulk-action', [InboundLogisticController::class, 'bulkAction'])->name('inbound-logistics.bulk-action');

Route::get('/admin/warehousing/storage-inventory', [InventoryController::class, 'index'])->name('admin.warehousing.storage-inventory');
Route::get('/inventory/search', [InventoryController::class, 'search'])->name('inventory.search');
Route::resource('inventory', InventoryController::class);
Route::post('/inventory/{id}/move', [InventoryController::class, 'move'])->name('inventory.move');

// Storage and Inventory Actions
Route::post('/inventory/{inventory}/request-supply', [InventoryController::class, 'requestSupply'])->name('inventory.request-supply');
Route::post('/inventory/{inventory}/return-item', [InventoryController::class, 'returnItem'])->name('inventory.return-item');
Route::post('/inventory/{inventory}/move-to-outbound', [InventoryController::class, 'moveToOutbound'])->name('inventory.move-to-outbound');
Route::get('/inventory/export', [InventoryController::class, 'export'])->name('inventory.export');
Route::post('/inventory/bulk-action', [InventoryController::class, 'bulkAction'])->name('inventory.bulk-action');
Route::get('/inventory/stats', [InventoryController::class, 'getStats'])->name('inventory.stats');
Route::get('/inventory/low-stock', [InventoryController::class, 'getLowStockItems'])->name('inventory.low-stock');

Route::get('/admin/warehousing/outbound-logistics', [OutboundLogisticController::class, 'index'])->name('admin.warehousing.outbound-logistics');
Route::get('/outbound-logistics/search', [OutboundLogisticController::class, 'search'])->name('outbound-logistics.search');
Route::resource('outbound-logistics', OutboundLogisticController::class)->except(['destroy'])->names([
    'index' => 'outbound-logistics.index',
    'create' => 'outbound-logistics.create',
    'store' => 'outbound-logistics.store',
    'show' => 'outbound-logistics.show',
    'edit' => 'outbound-logistics.edit',
    'update' => 'outbound-logistics.update',
]);

// Outbound Logistics Actions
Route::post('/outbound-logistics/{outboundLogistic}/ship', [OutboundLogisticController::class, 'shipItem'])->name('outbound-logistics.ship');
Route::post('/outbound-logistics/{outboundLogistic}/deliver', [OutboundLogisticController::class, 'deliverItem'])->name('outbound-logistics.deliver');
Route::post('/outbound-logistics/{outboundLogistic}/cancel', [OutboundLogisticController::class, 'cancelShipment'])->name('outbound-logistics.cancel');
Route::post('/outbound-logistics/{outboundLogistic}/process-supply', [OutboundLogisticController::class, 'processSupplyRequest'])->name('outbound-logistics.process-supply');
Route::get('/outbound-logistics/export', [OutboundLogisticController::class, 'export'])->name('outbound-logistics.export');
Route::post('/outbound-logistics/bulk-action', [OutboundLogisticController::class, 'bulkAction'])->name('outbound-logistics.bulk-action');
Route::get('/outbound-logistics/stats', [OutboundLogisticController::class, 'getStats'])->name('outbound-logistics.stats');
Route::get('/outbound-logistics/pending-supply', [OutboundLogisticController::class, 'getPendingSupplyRequests'])->name('outbound-logistics.pending-supply');
Route::get('/outbound-logistics/pending-supply', [OutboundLogisticController::class, 'getPendingSupplyRequests'])->name('outbound-logistics.pending-supply');

Route::get('/admin/warehousing/returns-management', [ReturnRefundController::class, 'index'])->name('admin.warehousing.returns-management');

Route::resource('returns-management', ReturnRefundController::class)->except(['destroy'])->names([
    'index' => 'returns-management.index',
    'create' => 'returns-management.create',
    'store' => 'returns-management.store',
    'show' => 'returns-management.show',
    'edit' => 'returns-management.edit',
    'update' => 'returns-management.update',
]);

// Procurement Routes
Route::get('/admin/procurement/request-supplies', [SupplyRequestController::class, 'index'])->name('admin.procurement.request-supplies');

Route::resource('supply-requests', SupplyRequestController::class)->names([
    'index' => 'supply-requests.index',
    'create' => 'supply-requests.create',
    'store' => 'supply-requests.store',
    'show' => 'supply-requests.show',
    'edit' => 'supply-requests.edit',
    'update' => 'supply-requests.update',
    'destroy' => 'supply-requests.destroy',
]);

// Supply Request Approval Routes
Route::post('/supply-requests/{supplyRequest}/approve', [SupplyRequestController::class, 'approve'])->name('supply-requests.approve');
Route::post('/supply-requests/{supplyRequest}/reject', [SupplyRequestController::class, 'reject'])->name('supply-requests.reject');

Route::get('/admin/procurement/create-purchase-order', [PurchaseOrderController::class, 'index'])->name('admin.procurement.create-purchase-order');

Route::resource('purchase-orders', PurchaseOrderController::class)->names([
    'index' => 'purchase-orders.index',
    'create' => 'purchase-orders.create',
    'store' => 'purchase-orders.store',
    'show' => 'purchase-orders.show',
    'edit' => 'purchase-orders.edit',
    'update' => 'purchase-orders.update',
    'destroy' => 'purchase-orders.destroy',
]);

// Purchase Order Approval Routes
Route::post('/purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
Route::post('/purchase-orders/{purchaseOrder}/reject', [PurchaseOrderController::class, 'reject'])->name('purchase-orders.reject');

Route::get('/admin/procurement/create-contract-reports', [ContractController::class, 'index'])->name('admin.procurement.create-contract-reports');

Route::resource('contracts', ContractController::class)->names([
    'index' => 'contracts.index',
    'create' => 'contracts.create',
    'store' => 'contracts.store',
    'show' => 'contracts.show',
    'edit' => 'contracts.edit',
    'update' => 'contracts.update',
    'destroy' => 'contracts.destroy',
]);

// Contract Management Routes
Route::post('/contracts/{contract}/renew', [ContractController::class, 'renew'])->name('contracts.renew');
Route::post('/contracts/{contract}/terminate', [ContractController::class, 'terminate'])->name('contracts.terminate');
Route::get('/contracts/export', [ContractController::class, 'export'])->name('contracts.export');
Route::post('/contracts/bulk-action', [ContractController::class, 'bulkAction'])->name('contracts.bulk-action');
Route::get('/contracts/stats', [ContractController::class, 'getStats'])->name('contracts.stats');
Route::get('/contracts/expiring-soon', [ContractController::class, 'getExpiringSoon'])->name('contracts.expiring-soon');
Route::get('/contracts/needing-renewal', [ContractController::class, 'getNeedingRenewal'])->name('contracts.needing-renewal');

Route::get('/admin/procurement/vendors', [VendorController::class, 'index'])->name('procurement.vendors');

// Vendor Management Routes
Route::resource('vendors', VendorController::class)->except(['index']);
Route::post('vendors/{vendor}/approve', [VendorController::class, 'approve'])->name('vendors.approve');
Route::get('vendors/export', [VendorController::class, 'export'])->name('vendors.export');

// Supplier Validation Routes
Route::prefix('vendors/{vendor}/validations')->name('vendors.validations.')->group(function () {
    Route::get('/', [App\Http\Controllers\Procurement\SupplierValidationController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Procurement\SupplierValidationController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Procurement\SupplierValidationController::class, 'store'])->name('store');
    Route::get('/{validation}/edit', [App\Http\Controllers\Procurement\SupplierValidationController::class, 'edit'])->name('edit');
    Route::put('/{validation}', [App\Http\Controllers\Procurement\SupplierValidationController::class, 'update'])->name('update');
    Route::delete('/{validation}', [App\Http\Controllers\Procurement\SupplierValidationController::class, 'destroy'])->name('destroy');
    Route::post('/{validation}/validate', [App\Http\Controllers\Procurement\SupplierValidationController::class, 'validateDocument'])->name('validate');
});

// Supplier Verification Routes
Route::prefix('vendors/{vendor}/verifications')->name('vendors.verifications.')->group(function () {
    Route::get('/', [App\Http\Controllers\Procurement\SupplierVerificationController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Procurement\SupplierVerificationController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Procurement\SupplierVerificationController::class, 'store'])->name('store');
    Route::get('/{verification}/edit', [App\Http\Controllers\Procurement\SupplierVerificationController::class, 'edit'])->name('edit');
    Route::put('/{verification}', [App\Http\Controllers\Procurement\SupplierVerificationController::class, 'update'])->name('update');
    Route::delete('/{verification}', [App\Http\Controllers\Procurement\SupplierVerificationController::class, 'destroy'])->name('destroy');
    Route::post('/{verification}/complete', [App\Http\Controllers\Procurement\SupplierVerificationController::class, 'complete'])->name('complete');
    Route::post('/{verification}/schedule', [App\Http\Controllers\Procurement\SupplierVerificationController::class, 'schedule'])->name('schedule');
});

// Procurement Requirements Routes
Route::prefix('procurement/requirements')->name('procurement.requirements.')->group(function () {
    Route::get('/', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'store'])->name('store');
    Route::get('/{requirement}', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'show'])->name('show');
    Route::get('/{requirement}/edit', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'edit'])->name('edit');
    Route::put('/{requirement}', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'update'])->name('update');
    Route::delete('/{requirement}', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'destroy'])->name('destroy');
    Route::post('/{requirement}/approve', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'approve'])->name('approve');
    Route::post('/{requirement}/reject', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'reject'])->name('reject');
    Route::post('/{requirement}/assign', [App\Http\Controllers\Procurement\ProcurementRequirementController::class, 'assign'])->name('assign');
});

// Supplier Post Management Routes
Route::prefix('admin/supplier-posts')->name('admin.supplier.posts.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\SupplierPostController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\SupplierPostController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\SupplierPostController::class, 'store'])->name('store');
    Route::get('/{post}/edit', [App\Http\Controllers\Admin\SupplierPostController::class, 'edit'])->name('edit');
    Route::put('/{post}', [App\Http\Controllers\Admin\SupplierPostController::class, 'update'])->name('update');
    Route::delete('/{post}', [App\Http\Controllers\Admin\SupplierPostController::class, 'destroy'])->name('destroy');
    Route::post('/{post}/toggle-featured', [App\Http\Controllers\Admin\SupplierPostController::class, 'toggleFeatured'])->name('toggle-featured');
    Route::post('/{post}/toggle-status', [App\Http\Controllers\Admin\SupplierPostController::class, 'toggleStatus'])->name('toggle-status');
});

// Asset Lifecycle & Maintenance Routes
Route::get('/admin/assetlifecycle/request-asset', [AssetRequestController::class, 'index'])->name('admin.assetlifecycle.request-asset');

Route::resource('asset-requests', AssetRequestController::class)->names([
    'index' => 'asset-requests.index',
    'create' => 'asset-requests.create',
    'store' => 'asset-requests.store',
    'show' => 'asset-requests.show',
    'edit' => 'asset-requests.edit',
    'update' => 'asset-requests.update',
    'destroy' => 'asset-requests.destroy',
]);

Route::get('/admin/assetlifecycle/asset-management', [AssetManagementController::class, 'index'])->name('admin.assetlifecycle.asset-management');

Route::get('/asset-management/search', [AssetManagementController::class, 'search'])->name('asset-management.search');
Route::get('/asset-management/export', [AssetManagementController::class, 'export'])->name('asset-management.export');
Route::resource('asset-management', AssetManagementController::class)->except(['destroy'])->names([
    'index' => 'asset-management.index',
    'create' => 'asset-management.create',
    'store' => 'asset-management.store',
    'show' => 'asset-management.show',
    'edit' => 'asset-management.edit',
    'update' => 'asset-management.update',
]);

// Asset Management Actions
Route::post('/asset-management/{asset}/dispose', [AssetManagementController::class, 'disposeAsset'])->name('asset-management.dispose');
Route::post('/asset-management/request-asset', [AssetManagementController::class, 'requestAsset'])->name('asset-management.request-asset');
Route::get('/asset-management/available', [AssetManagementController::class, 'getAvailableAssets'])->name('asset-management.available');
Route::get('/asset-management/disposal-candidates', [AssetManagementController::class, 'getAssetsForDisposal'])->name('asset-management.disposal-candidates');

Route::get('/admin/assetlifecycle/asset-maintenance', [AssetMaintenanceController::class, 'index'])->name('admin.assetlifecycle.asset-maintenance');

Route::resource('asset-maintenance', AssetMaintenanceController::class)->names([
    'index' => 'asset-maintenance.index',
    'create' => 'asset-maintenance.create',
    'store' => 'asset-maintenance.store',
    'show' => 'asset-maintenance.show',
    'edit' => 'asset-maintenance.edit',
    'update' => 'asset-maintenance.update',
    'destroy' => 'asset-maintenance.destroy',
]);

// Asset Maintenance Additional Routes
Route::get('/asset-maintenance/export', [AssetMaintenanceController::class, 'export'])->name('asset-maintenance.export');
Route::get('/asset-maintenance/asset-details/{assetId}', [AssetMaintenanceController::class, 'getAssetDetails'])->name('asset-maintenance.asset-details');
Route::post('/asset-maintenance/bulk-action', [AssetMaintenanceController::class, 'bulkAction'])->name('asset-maintenance.bulk-action');

// Logistic Tracking Routes
Route::resource('delivery-confirmation', DeliveryConfirmationController::class)->names([
    'index' => 'delivery-confirmation.index',
    'create' => 'delivery-confirmation.create',
    'store' => 'delivery-confirmation.store',
    'show' => 'delivery-confirmation.show',
    'edit' => 'delivery-confirmation.edit',
    'update' => 'delivery-confirmation.update',
    'destroy' => 'delivery-confirmation.destroy',
]);

Route::get('/admin/logistictracking/delivery-confirmation', [DeliveryConfirmationController::class, 'index'])->name('admin.logistictracking.delivery-confirmation');

Route::get('/admin/logistictracking/request-vehicle', function () {
    return view('admin.logistictracking.request-vehicle');
})->name('admin.logistictracking.request-vehicle');

Route::get('/admin/logistictracking/project-planning-request', [ProjectPlanningController::class, 'create'])->name('admin.logistictracking.project-planning-request');

Route::resource('project-planning', ProjectPlanningController::class)->names([
    'index' => 'project-planning.index',
    'create' => 'project-planning.create',
    'store' => 'project-planning.store',
    'show' => 'project-planning.show',
    'edit' => 'project-planning.edit',
    'update' => 'project-planning.update',
    'destroy' => 'project-planning.destroy',
]);

Route::get('/admin/logistictracking/reports', [LogisticsReportController::class, 'index'])->name('admin.logistictracking.reports');

Route::resource('logistics-reports', LogisticsReportController::class)->names([
    'index' => 'logistics-reports.index',
    'create' => 'logistics-reports.create',
    'store' => 'logistics-reports.store',
    'show' => 'logistics-reports.show',
    'edit' => 'logistics-reports.edit',
    'update' => 'logistics-reports.update',
    'destroy' => 'logistics-reports.destroy',
]);

// Document Tracking Routes
Route::get('/admin/documenttracking/document-request', [DocumentRequestController::class, 'index'])->name('admin.documenttracking.document-request');

Route::resource('document-requests', DocumentRequestController::class)->names([
    'index' => 'document-requests.index',
    'create' => 'document-requests.create',
    'store' => 'document-requests.store',
    'show' => 'document-requests.show',
    'edit' => 'document-requests.edit',
    'update' => 'document-requests.update',
    'destroy' => 'document-requests.destroy',
]);

Route::get('/admin/documenttracking/list-document-request', [DocumentRequestController::class, 'listRequests'])->name('admin.documenttracking.list-document-request');

Route::get('/admin/documenttracking/upload-document-tracking', [UploadedDocumentController::class, 'index'])->name('admin.documenttracking.upload-document-tracking');
Route::resource('uploaded-documents', UploadedDocumentController::class)->names([
    'index' => 'uploaded-documents.index',
    'create' => 'uploaded-documents.create',
    'store' => 'uploaded-documents.store',
    'show' => 'uploaded-documents.show',
    'edit' => 'uploaded-documents.edit',
    'update' => 'uploaded-documents.update',
    'destroy' => 'uploaded-documents.destroy',
]);
Route::get('uploaded-documents/{uploadedDocument}/download', [UploadedDocumentController::class, 'download'])->name('uploaded-documents.download');

Route::get('/admin/documenttracking/reports', [DocumentReportController::class, 'index'])->name('admin.documenttracking.reports');

// Document Reports Resource Routes
Route::resource('document-reports', DocumentReportController::class)->names([
    'index' => 'document-reports.index',
    'create' => 'document-reports.create',
    'store' => 'document-reports.store',
    'show' => 'document-reports.show',
    'edit' => 'document-reports.edit',
    'update' => 'document-reports.update',
    'destroy' => 'document-reports.destroy',
]);

// Admin Settings Routes
Route::get('/admin/adminsettings/users-roles', [UserController::class, 'index'])->name('admin.adminsettings.users-roles');

// User Management Resource Routes
Route::resource('users', UserController::class)->names([
    'index' => 'users.index',
    'create' => 'users.create',
    'store' => 'users.store',
    'show' => 'users.show',
    'edit' => 'users.edit',
    'update' => 'users.update',
    'destroy' => 'users.destroy',
]);

Route::put('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

Route::get('/admin/adminsettings/audit-logs', [AuditLogController::class, 'index'])->name('admin.adminsettings.audit-logs');

// Vehicle Request Routes
Route::resource('vehicle-requests', VehicleRequestController::class)->names([
    'index' => 'vehicle-requests.index',
    'create' => 'vehicle-requests.create',
    'store' => 'vehicle-requests.store',
    'show' => 'vehicle-requests.show',
]);

Route::get('/vehicle-requests/search-reservation', [VehicleRequestController::class, 'searchReservation'])->name('vehicle-requests.search-reservation');
Route::post('/vehicle-requests/update-status', [VehicleRequestController::class, 'updateStatus'])->name('vehicle-requests.update-status');

// Audit Log Management Routes
Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
Route::get('audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
Route::delete('audit-logs/clear', [AuditLogController::class, 'clearOldLogs'])->name('audit-logs.clear');

    // New Inventory Management Routes
    Route::get('/inventory/dashboard', [NewInventoryController::class, 'dashboard'])->name('inventory.dashboard');
    Route::get('/inventory/low-stock', [NewInventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('/inventory/out-of-stock', [NewInventoryController::class, 'outOfStock'])->name('inventory.out-of-stock');
    Route::get('/inventory/{inventory}/procurement-request', [NewInventoryController::class, 'createProcurementRequest'])->name('inventory.procurement-request');
    Route::post('/inventory/{inventory}/procurement-request', [NewInventoryController::class, 'storeProcurementRequest'])->name('inventory.procurement-request.store');

    // New Procurement Routes
    Route::get('/procurement', [ProcurementController::class, 'index'])->name('procurement.index');
    Route::get('/procurement/create', [ProcurementController::class, 'create'])->name('procurement.create');
    Route::post('/procurement', [ProcurementController::class, 'store'])->name('procurement.store');
    Route::get('/procurement/{supplyRequest}', [ProcurementController::class, 'show'])->name('procurement.show');
    Route::post('/procurement/{supplyRequest}/approve', [ProcurementController::class, 'approve'])->name('procurement.approve');
    Route::post('/procurement/{supplyRequest}/mark-ordered', [ProcurementController::class, 'markAsOrdered'])->name('procurement.mark-ordered');
    Route::delete('/procurement/{supplyRequest}', [ProcurementController::class, 'destroy'])->name('procurement.destroy');

    // New Logistics Routes
    Route::get('/logistics', [LogisticsController::class, 'dashboard'])->name('logistics.dashboard');
    Route::get('/logistics/outbound/create', [LogisticsController::class, 'outboundCreate'])->name('logistics.outbound.create');
    Route::post('/logistics/outbound', [LogisticsController::class, 'outboundStore'])->name('logistics.outbound.store');
    Route::get('/logistics/inbound/create', [LogisticsController::class, 'inboundCreate'])->name('logistics.inbound.create');
    Route::post('/logistics/inbound', [LogisticsController::class, 'inboundStore'])->name('logistics.inbound.store');
    Route::get('/logistics/history', [LogisticsController::class, 'history'])->name('logistics.history');
    Route::get('/logistics/pending', [LogisticsController::class, 'pendingDeliveries'])->name('logistics.pending');

});

// Website Supplier Routes
Route::prefix('suppliers')->name('website.suppliers.')->group(function () {
    // Supplier directory page
    Route::get('/', [App\Http\Controllers\Website\SupplierController::class, 'index'])->name('index');
    
    // Supplier registration page
    Route::get('/register', [App\Http\Controllers\Website\SupplierController::class, 'register'])->name('register');
    
    // Handle supplier registration submission
    Route::post('/register', [App\Http\Controllers\Website\SupplierController::class, 'store'])->name('register.submit');
    
    // Supplier detail page
    Route::get('/{supplier:slug}', [App\Http\Controllers\Website\SupplierController::class, 'show'])->name('show');
    
    // Supplier posts
    Route::get('/posts/{post:slug}', [App\Http\Controllers\Website\SupplierController::class, 'post'])->name('post');
});

// Website home route (can be customized)
Route::get('/vendor', [App\Http\Controllers\Website\SupplierController::class, 'home'])->name('website.home');

