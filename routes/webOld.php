<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\StoreController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\RackController;
use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Backend\CardController;
use App\Http\Controllers\Backend\SupplierPaymentController;
use App\Http\Controllers\Backend\SupplierPaymentAlertController;



use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\DirectAddProductStockInSellController;



Route::get('/help', function () {
    return view('help');
});

Auth::routes(['verify' => true]);

// Auth::routes();
/*
|--------------------------------------------------------------------------
| Backend Route
|--------------------------------------------------------------------------
*/
Auth::routes();
Route::group(['auth', 'role:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('report-pdf', [HomeController::class, 'indexPdf'])->name('report.pdf');

    Route::resource('users', UserController::class);
    Route::resource('employees', EmployeeController::class);

    Route::resource('stores', StoreController::class);
    Route::resource('customers', CustomerController::class);
    
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);

    Route::resource('suppliers', SupplierController::class);

    Route::resource('racks', RackController::class);

    Route::prefix('bank')->group(function(){
        Route::resource('accounts', AccountController::class);
        Route::resource('cards', CardController::class);
    });

    // Routes for Supplier Payments
    Route::resource('supplier-payments', SupplierPaymentController::class);
    
    // Routes for Supplier Payment Alerts
    Route::resource('supplier-payment-alerts', SupplierPaymentAlertController::class);

    // notification 
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll');



    Route::resource('expenses', App\Http\Controllers\Backend\ExpenseController::class);
    
    Route::resource('assets', App\Http\Controllers\Backend\AssetController::class);

    Route::prefix('emplyees')->group(function(){
        Route::resource('salarypays', App\Http\Controllers\Backend\EmployeeSalaryController::class);
    });

    Route::resource('products', ProductController::class);

    Route::prefix('products')->group(function () {
        Route::get('/barcode/{id}/{type}', [ProductController::class, 'generateBarcode'])->name('barcode');
        Route::post('toggle-status/{product}', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::post('direct-store-sell', [DirectAddProductStockInSellController::class, 'store'])->name('product-direct-store-sell');
    });

    Route::get('product-direct-stock-ins', [DirectAddProductStockInSellController::class, 'create'])->name('product-direct-stock-ins');
    Route::post('product-direct-stock-ins', [DirectAddProductStockInSellController::class, 'storeDirect'])->name('product-stock-ins.direct');


    // stock in
    Route::resource('stockins', App\Http\Controllers\Backend\StockInController::class);

    //stock details update add delete
    Route::post('/update-stock', [App\Http\Controllers\Backend\StockInController::class, 'updateStock'])->name('stockins.updateStock');
    Route::post('/delete-stock', [App\Http\Controllers\Backend\StockInController::class, 'deleteStock'])->name('stockins.deleteStock');
    Route::post('/add-stock-modify', [App\Http\Controllers\Backend\StockInController::class, 'addStockModify'])->name('stockins.addStockModify');

    // report
    Route::get('/report', [App\Http\Controllers\Backend\ReportController::class, 'generateReport'])->name('report.index');

    // Define route for generating stock report
    Route::get('/stock-report', [App\Http\Controllers\Backend\ReportController::class, 'generateStockReport'])->name('report.stock');

    Route::get('/supplier-report', [App\Http\Controllers\Backend\SupplierReportController::class, 'index'])->name('supplier.report');
    Route::get('/profit-report', [App\Http\Controllers\Backend\ProfitReportController::class, 'index'])->name('profit.report');

    Route::resource('dailyreports', App\Http\Controllers\Backend\DailyReportController::class);

    // invoice
    Route::resource('invoices', App\Http\Controllers\Backend\InvoiceController::class);
    // invoice for print
    Route::get('/invoice-show-for-print/{id}', [App\Http\Controllers\Backend\InvoiceController::class, 'invoice_show_for_print'])->name('invoice_show_for_print');
    
    // download invoice list
    Route::get('invoice-list/pdf-download', [App\Http\Controllers\Backend\InvoiceController::class, 'downloadInvoiceListPDF'])->name('invoices.pdf.download');
     
    // download single pdf
    Route::get('/invoice/pdf/{id}', [App\Http\Controllers\Backend\InvoiceController::class, 'downloadInvoicePDF'])->name('invoice.pdf');
    
    Route::get('/invoice/delete/{id}', [App\Http\Controllers\Backend\InvoiceController::class, 'destroy'])->name('delete-invoice');
    
    // 
    Route::get('/json/invoice/{id}', [App\Http\Controllers\Backend\InvoiceController::class, 'jsonInvoice'])->name('json.invoice');

    Route::post('/sell-product-update', [App\Http\Controllers\Backend\InvoiceController::class, 'sellProductUpdate'])->name('sell-product.update');
    Route::post('/sell-product-update-qty', [App\Http\Controllers\Backend\InvoiceController::class, 'sellProductUpdateQty'])->name('sell-product.update-qty');
    Route::post('/sell-product-delete', [App\Http\Controllers\Backend\InvoiceController::class, 'sellProductDelete'])->name('sell-product.delete');
    

    Route::get('/sell-products', [App\Http\Controllers\Backend\SellProductController::class, 'index'])->name('sell-products.index');
    Route::get('/sell-products/pdf', [App\Http\Controllers\Backend\SellProductController::class, 'downloadPDF'])->name('sell-products.pdf');

    

    // Resource route for menu management (only accessible to admin)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // for default add
        Route::get('insert-roles', [App\Http\Controllers\Backend\RoleMenuController::class, 'insertRoles']);
        Route::get('insert-menus', [App\Http\Controllers\Backend\MenuController::class, 'insertMenus']);
        Route::resource('menus', App\Http\Controllers\Backend\MenuController::class);
        Route::resource('role-menus', App\Http\Controllers\Backend\RoleMenuController::class);
        // for admin default add
        Route::get('role_menus/assign_admin_menus', [App\Http\Controllers\Backend\RoleMenuController::class, 'assignMenusToAdmin'])->name('role_menus.assign_admin_menus');

    });
    // units 
    Route::resource('units', App\Http\Controllers\Backend\UnitController::class);
    // customer payments 
    Route::resource('customer-payments', App\Http\Controllers\Backend\CustomerPaymentController::class);
    Route::patch('/customer-payments/{id}', [CustomerPaymentController::class, 'update'])->name('customer-payments.update');

    // site info
   Route::match(array('GET','POST'),'/site-info', [App\Http\Controllers\Backend\SettingsController::class, 'edit'])->name('site-info');

   Route::match(array('GET','POST'),'/profile', [App\Http\Controllers\Backend\ProfileController::class, 'profile'])->name('profile');

   Route::get('change-password', [App\Http\Controllers\Backend\ProfileController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('change-password', [App\Http\Controllers\Backend\ProfileController::class, 'changePassword'])->name('change-password.update');

    Route::prefix('api')->name('api.')->group(function() {
    // Categories
    Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'categories'])->name('categories');
    Route::get('/subcategories/{id}', [App\Http\Controllers\Api\CategoryController::class, 'subcategories'])->name('subcategories');

    // Brands
    Route::get('/brands', [App\Http\Controllers\Api\BrandController::class, 'brands'])->name('brands');

    // Products
    Route::get('/product/{id}', [App\Http\Controllers\Api\ProductController::class, 'product_by_id'])->name('product');
    Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'products'])->name('products');

    // Suppliers
    Route::get('/supplier/{id}', [App\Http\Controllers\Api\SupplierController::class, 'supplier_by_id'])->name('supplier');

    // Racks
    Route::get('/racks/{store_id?}', [App\Http\Controllers\Api\RackController::class, 'racks'])->name('racks');

    // Cart
    Route::post('/add-to-cart', [App\Http\Controllers\Backend\CartController::class, 'add_to_cart'])->name('add-to-cart');
    Route::get('/cart-list', [App\Http\Controllers\Backend\CartController::class, 'cart_list'])->name('cart-list');
    Route::post('/update-to-cart', [App\Http\Controllers\Backend\CartController::class, 'update_to_cart'])->name('update-to-cart');
    Route::get('/remove-to-cart/{id}', [App\Http\Controllers\Backend\CartController::class, 'remove_to_cart'])->name('remove-to-cart');

    // Customer
    Route::post('/get-customer', [App\Http\Controllers\Api\CustomerController::class, 'getCustomer'])->name('get-customer');
});

});
