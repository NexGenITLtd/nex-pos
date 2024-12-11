<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SmsSendController;
use App\Http\Controllers\Backend\{
    NotificationController, HomeController, UserController, EmployeeController, StoreController, CustomerController,
    CategoryController, BrandController, SupplierController, RackController, AccountController, CardController,
    SupplierPaymentController, SupplierPaymentAlertController, ProductController, DirectAddProductStockInSellController,
    ExpenseController, AssetController, EmployeeSalaryController, StockInController, ReportController, SupplierReportController,
    ProfitReportController, DailyReportController, InvoiceController, SellProductController, ReturnSellProductController, MenuController,
    SettingsController, ProfileController, UnitController, SmsSettingController, CustomerPaymentController, CartController,UserDataController,OwnerDepositController,TransactionController
};
use App\Http\Controllers\Api\{
    CategoryController as ApiCategoryController, BrandController as ApiBrandController, ProductController as ApiProductController,
    SupplierController as ApiSupplierController, RackController as ApiRackController, CustomerController as ApiCustomerController
};

// Public Routes
Route::view('/help', 'help');
Route::view('/unauthorized', 'errors.unauthorized')->name('unauthorized');

// phone number collection 
Route::resource('user-phone-data', UserDataController::class);

Route::post('/user-phone-data/get-columns', [UserDataController::class, 'getColumns'])->name('user-phone-data.get-columns');
Route::post('/user-phone-data/delete-all', [UserDataController::class, 'deleteAll'])->name('user-phone-data.delete-all');


Route::group(['middleware' => ['role:super-admin|admin']], function() {

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);

    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);

});


Route::resources([
    'menus' => MenuController::class,
]);
Route::post('menus/update-order', [MenuController::class, 'updateOrder'])->name('menus.updateOrder');

// Auth Routes
Auth::routes(['verify' => true]);

// Backend Routes (Protected by Middleware)
Route::group(['middleware' => ['auth', 'role:super-admin|admin|station|staff']], function () {
    // Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('report-pdf', [HomeController::class, 'indexPdf'])->name('report.pdf');

    // Resources
    Route::resources([
        // 'users' => UserController::class,
        'employees' => EmployeeController::class,
        'stores' => StoreController::class,
        'customers' => CustomerController::class,
        'categories' => CategoryController::class,
        'brands' => BrandController::class,
        'suppliers' => SupplierController::class,
        'racks' => RackController::class,
        'expenses' => ExpenseController::class,
        'assets' => AssetController::class,
        'products' => ProductController::class,
        'stockins' => StockInController::class,
        'dailyreports' => DailyReportController::class,
        'invoices' => InvoiceController::class,
        'units' => UnitController::class,
        'customer-payments' => CustomerPaymentController::class,
        'sms-settings' => SmsSettingController::class,
        'owner-deposits' => OwnerDepositController::class,
        'transactions' => TransactionController::class,
    ]);
    
    Route::post('sms/send', [SmsSendController::class, 'send'])->name('sms.send');

    // Bank Management
    Route::prefix('bank')->group(function () {
        Route::resources([
            'accounts' => AccountController::class,
            'cards' => CardController::class,
        ]);
    });

    // Supplier Payments & Alerts
    Route::resources([
        'supplier-payments' => SupplierPaymentController::class,
        'supplier-payment-alerts' => SupplierPaymentAlertController::class,
    ]);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read.single');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy.single');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy.all');

    // Employee Salaries
    Route::resource('salarypays', EmployeeSalaryController::class);
    // Route::prefix('employees')->group(function () {
       
    // });

    // Product Management
    Route::prefix('products')->group(function () {
        Route::get('/barcode/{id}/{type}', [ProductController::class, 'generateBarcode'])->name('barcode');
        Route::post('toggle-status/{product}', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::post('direct-store-sell', [DirectAddProductStockInSellController::class, 'store'])->name('product-direct-store-sell');
    });
    Route::get('product-direct-stock-ins', [DirectAddProductStockInSellController::class, 'create'])->name('product-direct-stock-ins');
    Route::post('product-direct-stock-ins', [DirectAddProductStockInSellController::class, 'storeDirect'])->name('product-stock-ins.direct');

    // Stock Operations
    Route::post('/update-stock', [StockInController::class, 'updateStock'])->name('stockins.updateStock');
    Route::post('/delete-stock', [StockInController::class, 'deleteStock'])->name('stockins.deleteStock');
    Route::post('/add-stock-modify', [StockInController::class, 'addStockModify'])->name('stockins.addStockModify');

    // Reports
    Route::get('/reports', [ReportController::class, 'generateReport'])->name('reports.index');
    Route::get('/stock-report', [ReportController::class, 'generateStockReport'])->name('report.stock');
    Route::get('/supplier-report', [SupplierReportController::class, 'index'])->name('supplier.report');
    Route::get('/profit-report', [ProfitReportController::class, 'index'])->name('profit.report');

    // Invoice Operations
    Route::get('/invoice-show-for-print/{id}', [InvoiceController::class, 'invoice_show_for_print'])->name('invoice_show_for_print');
    Route::get('invoice-list/pdf-download', [InvoiceController::class, 'downloadInvoiceListPDF'])->name('invoices.pdf.download');
    Route::get('/invoice/pdf/{id}', [InvoiceController::class, 'downloadInvoicePDF'])->name('invoice.pdf');
    Route::get('/invoice/delete/{id}', [InvoiceController::class, 'destroy'])->name('delete-invoice');
    Route::get('/json/invoice/{id}', [InvoiceController::class, 'jsonInvoice'])->name('json.invoice');
    Route::post('/sell-product-update', [InvoiceController::class, 'sellProductUpdate'])->name('sell-product.update');
    Route::post('/sell-product-update-qty', [InvoiceController::class, 'sellProductUpdateQty'])->name('sell-product.update-qty');
    Route::post('/sell-product-delete', [InvoiceController::class, 'sellProductDelete'])->name('sell-product.delete');

    // Sell Products
    Route::get('/sell-products', [SellProductController::class, 'index'])->name('sell-products.index');
    Route::get('/return-sell-products', [ReturnSellProductController::class, 'index'])->name('return-sell-products.index');
    Route::get('/sell-products/pdf', [SellProductController::class, 'downloadPDF'])->name('sell-products.pdf');
    Route::get('/return-sell-products/pdf', [ReturnSellProductController::class, 'downloadPDF'])->name('return-sell-products.pdf');

    

    // Settings
    Route::match(['GET', 'POST'], '/site-info', [SettingsController::class, 'edit'])->name('site-info');
    Route::match(['GET', 'POST'], '/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('change-password', [ProfileController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('change-password', [ProfileController::class, 'changePassword'])->name('change-password.update');

    // API Routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/categories', [ApiCategoryController::class, 'categories'])->name('categories');
        Route::get('/subcategories/{id}', [ApiCategoryController::class, 'subcategories'])->name('subcategories');
        Route::get('/brands', [ApiBrandController::class, 'brands'])->name('brands');
        Route::get('/product/{id}', [ApiProductController::class, 'product_by_id'])->name('product');
        Route::get('/products', [ApiProductController::class, 'products'])->name('products');
        Route::get('/supplier/{id}', [ApiSupplierController::class, 'supplier_by_id'])->name('supplier');
        Route::get('/racks/{store_id?}', [ApiRackController::class, 'racks'])->name('racks');
        Route::post('/get-customer', [ApiCustomerController::class, 'getCustomer'])->name('get-customer');


        // Cart Operations
        Route::post('/add-to-cart', [CartController::class, 'store'])->name('add-to-cart');
        Route::get('/cart-list', [CartController::class, 'index'])->name('cart-list');
        Route::post('/update-to-cart', [CartController::class, 'update'])->name('update-to-cart');
        Route::get('/remove-to-cart/{id}', [CartController::class, 'destroy'])->name('remove-to-cart');
    });

});
