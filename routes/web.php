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

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Login logic will be handled by Laravel's authentication
    return redirect('/admin/dashboard');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    // Registration logic will be handled by Laravel's authentication
    return redirect('/admin/dashboard');
});

Route::post('/logout', function () {
    // Logout logic will be handled by Laravel's authentication
    return redirect('/login');
})->name('logout');

Route::resource('inbound-logistics', InboundLogisticController::class);

// Admin Dashboard Routes - No Security
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index');
})->name('admin.dashboard');

Route::get('/admin/warehousing/inbound-logistics', [InboundLogisticController::class, 'index'])->name('admin.warehousing.inbound-logistics');

Route::get('/admin/warehousing/storage-inventory', [InventoryController::class, 'index'])->name('admin.warehousing.storage-inventory');

Route::resource('inventory', InventoryController::class);
Route::post('/inventory/{id}/move', [InventoryController::class, 'move'])->name('inventory.move');

Route::get('/admin/warehousing/outbound-logistics', [OutboundLogisticController::class, 'index'])->name('admin.warehousing.outbound-logistics');

Route::resource('outbound-logistics', OutboundLogisticController::class)->names([
    'index' => 'outbound-logistics.index',
    'create' => 'outbound-logistics.create',
    'store' => 'outbound-logistics.store',
    'show' => 'outbound-logistics.show',
    'edit' => 'outbound-logistics.edit',
    'update' => 'outbound-logistics.update',
    'destroy' => 'outbound-logistics.destroy',
]);

Route::get('/admin/warehousing/returns-management', [ReturnRefundController::class, 'index'])->name('admin.warehousing.returns-management');

Route::resource('returns-management', ReturnRefundController::class)->names([
    'index' => 'returns-management.index',
    'create' => 'returns-management.create',
    'store' => 'returns-management.store',
    'show' => 'returns-management.show',
    'edit' => 'returns-management.edit',
    'update' => 'returns-management.update',
    'destroy' => 'returns-management.destroy',
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

Route::get('/admin/procurement/vendors', [VendorController::class, 'index'])->name('procurement.vendors');

// Vendor Management Routes
Route::resource('vendors', VendorController::class)->except(['index']);
Route::get('vendors/export', [VendorController::class, 'export'])->name('vendors.export');

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

Route::resource('asset-management', AssetManagementController::class)->names([
    'index' => 'asset-management.index',
    'create' => 'asset-management.create',
    'store' => 'asset-management.store',
    'show' => 'asset-management.show',
    'edit' => 'asset-management.edit',
    'update' => 'asset-management.update',
    'destroy' => 'asset-management.destroy',
]);

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

// Audit Log Management Routes
Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
Route::get('audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
Route::delete('audit-logs/clear', [AuditLogController::class, 'clearOldLogs'])->name('audit-logs.clear');
