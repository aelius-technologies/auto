<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('command:clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return "config, cache, and view cleared successfully";
});

Route::get('command:config', function() {
    Artisan::call('config:cache');
    return "config cache successfully";
});

Route::get('command:key', function() {
    Artisan::call('key:generate');
    return "Key generate successfully";
});

Route::get('command:migrate', function() {
    Artisan::call('migrate:refresh');
    return "Database migration generated";
});

Route::get('command:seed', function() {
    Artisan::call('db:seed');
    return "Database seeding generated";
});

Route::group(['middleware' => ['prevent-back-history', 'mail-service']], function(){
    Route::group(['middleware' => ['guest']], function () {
        Route::get('/', 'AuthController@login')->name('login');
        Route::post('signin', 'AuthController@signin')->name('signin');

        Route::get('forgot-password', 'AuthController@forgot_password')->name('forgot.password');
        Route::post('password-forgot', 'AuthController@password_forgot')->name('password.forgot');
        Route::get('reset-password/{string}', 'AuthController@reset_password')->name('reset.password');
        Route::post('recover-password', 'AuthController@recover_password')->name('recover.password');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logout', 'AuthController@logout')->name('logout');

        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        /** access control */
            /** role */
                Route::any('role', 'RoleController@index')->name('role');
                Route::get('role/create', 'RoleController@create')->name('role.create');
                Route::post('role/insert', 'RoleController@insert')->name('role.insert');
                Route::get('role/edit', 'RoleController@edit')->name('role.edit');
                Route::patch('role/update/{id?}', 'RoleController@update')->name('role.update');
                Route::get('role/view', 'RoleController@view')->name('role.view');
                Route::post('role/delete', 'RoleController@delete')->name('role.delete');
            /** role */

            /** permission */
                Route::any('permission', 'PermissionController@index')->name('permission');
                Route::get('permission/create', 'PermissionController@create')->name('permission.create');
                Route::post('permission/insert', 'PermissionController@insert')->name('permission.insert');
                Route::get('permission/edit', 'PermissionController@edit')->name('permission.edit');
                Route::patch('permission/update/{id?}', 'PermissionController@update')->name('permission.update');
                Route::get('permission/view', 'PermissionController@view')->name('permission.view');
                Route::post('permission/delete', 'PermissionController@delete')->name('permission.delete');
            /** permission */

            /** access */
                Route::any('access_permission', 'AccessController@index')->name('access_permission');
                Route::get('access_permission/edit', 'AccessController@edit')->name('access_permission.edit');
                Route::patch('access_permission/update/{id?}', 'AccessController@update')->name('access_permission.update');
                Route::get('access_permission/view', 'AccessController@view')->name('access_permission.view');
            /** access */
        /** access control */
        
        /** users */
            Route::any('user', 'UserController@index')->name('user');
            Route::get('user/create', 'UserController@create')->name('user.create');
            Route::post('user/insert', 'UserController@insert')->name('user.insert');
            Route::get('user/view/{id?}', 'UserController@view')->name('user.view');
            Route::get('user/edit/{id?}', 'UserController@edit')->name('user.edit');
            Route::patch('user/update', 'UserController@update')->name('user.update');
            Route::post('user/change-status', 'UserController@change_status')->name('user.change.status');
        /** users */
        
        /** Order Booking Fourm (OBF) */
            Route::any('obf', 'ObfController@index')->name('obf');
            Route::get('obf/get_obf_master_data', 'ObfController@get_obf_master_data')->name('obf.get_master_data');
            Route::get('obf/create', 'ObfController@create')->name('obf.create');
            Route::post('obf/insert', 'ObfController@insert')->name('obf.insert');
            Route::get('obf/view/{id?}', 'ObfController@view')->name('obf.view');
            Route::get('obf/edit/{id?}', 'ObfController@edit')->name('obf.edit');
            Route::patch('obf/update', 'ObfController@update')->name('obf.update');
            Route::post('obf/change-status', 'ObfController@change_status')->name('obf.change_status');
            Route::post('obf/profile-remove', 'ObfController@obf_profile_remove')->name('obf.profile.remove');
        /** Order Booking Fourm (OBF) */
        
        /** OBF Approval */
            Route::any('obf_approval', 'ObfApprovalController@index')->name('obf_approval');
            Route::get('obf_approval/view/{id?}', 'ObfApprovalController@view')->name('obf_approval.view');
            Route::post('obf_approval/change-status', 'ObfApprovalController@change_status')->name('obf_approval.change.status');
        /** OBF Approval */

        /** Cash Receipt */
            Route::any('cash_receipt', 'CashReceiptController@index')->name('cash_receipt');  
            Route::get('cash_receipt/view/{id?}', 'CashReceiptController@view')->name('cash_receipt.view');
            Route::post('cash_receipt/change-status', 'CashReceiptController@change_status')->name('cash_receipt.change.status');
            Route::get('cash_receipt/create', 'CashReceiptController@create')->name('cash_receipt.create');
            Route::post('cash_receipt/insert', 'CashReceiptController@insert')->name('cash_receipt.insert');
            Route::get('cash_receipt/generate_cash_receipt', 'CashReceiptController@generate_cash_receipt')->name('cash_receipt.generate_cash_receipt');
        /** Cash Receipt */

        /** Account Approval */
            Route::any('account_approval', 'AccountApprovalController@index')->name('account_approval');
            Route::get('account_approval/view/{id?}', 'AccountApprovalController@view')->name('account_approval.view');
            Route::post('account_approval/change-status', 'AccountApprovalController@change_status')->name('account_approval.change.status');
        /** Account Approval */

        /** product */
            Route::any('products', 'ProductController@index')->name('products');
            Route::get('products/create', 'ProductController@create')->name('products.create');
            Route::post('products/insert', 'ProductController@insert')->name('products.insert');
            Route::get('products/view/{id?}', 'ProductController@view')->name('products.view');
            Route::get('products/edit/{id?}', 'ProductController@edit')->name('products.edit');
            Route::patch('products/update', 'ProductController@update')->name('products.update');
            Route::post('products/change-status', 'ProductController@change_status')->name('products.change.status');
        /** product */

        /** taxes */
            Route::any('tax', 'TaxesController@index')->name('tax');
            Route::get('tax/create', 'TaxesController@create')->name('tax.create');
            Route::post('tax/insert', 'TaxesController@insert')->name('tax.insert');
            Route::get('tax/view/{id?}', 'TaxesController@view')->name('tax.view');
            Route::get('tax/edit/{id?}', 'TaxesController@edit')->name('tax.edit');
            Route::patch('tax/update', 'TaxesController@update')->name('tax.update');
            Route::post('tax/change-status', 'TaxesController@change_status')->name('tax.change.status');
        /** taxes */

        /** insurance */
            Route::any('insurance', 'InsuranceController@index')->name('insurance');
            Route::get('insurance/create', 'InsuranceController@create')->name('insurance.create');
            Route::post('insurance/insert', 'InsuranceController@insert')->name('insurance.insert');
            Route::get('insurance/view/{id?}', 'InsuranceController@view')->name('insurance.view');
            Route::get('insurance/edit/{id?}', 'InsuranceController@edit')->name('insurance.edit');
            Route::patch('insurance/update', 'InsuranceController@update')->name('insurance.update');
            Route::post('insurance/change-status', 'InsuranceController@change_status')->name('insurance.change.status');
        /** insurance */

        /** extand_warranty */
            Route::any('extand_warranties', 'ExtandWarrantyController@index')->name('extand_warranties');
            Route::get('extand_warranties/create', 'ExtandWarrantyController@create')->name('extand_warranties.create');
            Route::post('extand_warranties/insert', 'ExtandWarrantyController@insert')->name('extand_warranties.insert');
            Route::get('extand_warranties/view/{id?}', 'ExtandWarrantyController@view')->name('extand_warranties.view');
            Route::get('extand_warranties/edit/{id?}', 'ExtandWarrantyController@edit')->name('extand_warranties.edit');
            Route::patch('extand_warranties/update', 'ExtandWarrantyController@update')->name('extand_warranties.update');
            Route::post('extand_warranties/change-status', 'ExtandWarrantyController@change_status')->name('extand_warranties.change.status');
        /** extand_warranty */

        /** fasttag */
            Route::any('fasttag', 'FasttagController@index')->name('fasttag');
            Route::get('fasttag/create', 'FasttagController@create')->name('fasttag.create');
            Route::post('fasttag/insert', 'FasttagController@insert')->name('fasttag.insert');
            Route::get('fasttag/view/{id?}', 'FasttagController@view')->name('fasttag.view');
            Route::get('fasttag/edit/{id?}', 'FasttagController@edit')->name('fasttag.edit');
            Route::patch('fasttag/update', 'FasttagController@update')->name('fasttag.update');
            Route::post('fasttag/change-status', 'FasttagController@change_status')->name('fasttag.change.status');
        /** fasttag */

        /** finance */
            Route::any('finance', 'FinanceController@index')->name('finance');
            Route::get('finance/create', 'FinanceController@create')->name('finance.create');
            Route::post('finance/insert', 'FinanceController@insert')->name('finance.insert');
            Route::get('finance/view/{id?}', 'FinanceController@view')->name('finance.view');
            Route::get('finance/edit/{id?}', 'FinanceController@edit')->name('finance.edit');
            Route::patch('finance/update', 'FinanceController@update')->name('finance.update');
            Route::post('finance/change-status', 'FinanceController@change_status')->name('finance.change.status');
        /** finance */

         /** branch */
            Route::any('branches', 'BranchController@index')->name('branches');
            Route::get('branches/create', 'BranchController@create')->name('branches.create');
            Route::post('branches/insert', 'BranchController@insert')->name('branches.insert');
            Route::get('branches/view/{id?}', 'BranchController@view')->name('branches.view');
            Route::get('branches/edit/{id?}', 'BranchController@edit')->name('branches.edit');
            Route::patch('branches/update', 'BranchController@update')->name('branches.update');
            Route::post('branches/change-status', 'BranchController@change_status')->name('branches.change.status');
         /** branch */

         /** department */
            Route::any('department', 'DepartmentController@index')->name('department');
            Route::get('department/create', 'DepartmentController@create')->name('department.create');
            Route::post('department/insert', 'DepartmentController@insert')->name('department.insert');
            Route::get('department/view/{id?}', 'DepartmentController@view')->name('department.view');
            Route::get('department/edit/{id?}', 'DepartmentController@edit')->name('department.edit');
            Route::patch('department/update', 'DepartmentController@update')->name('department.update');
            Route::post('department/change-status', 'DepartmentController@change_status')->name('department.change.status');
         /** department */

        /** lead */
            Route::any('lead', 'LeadController@index')->name('lead');
            Route::get('lead/create', 'LeadController@create')->name('lead.create');
            Route::post('lead/insert', 'LeadController@insert')->name('lead.insert');
            Route::get('lead/view/{id?}', 'LeadController@view')->name('lead.view');
            Route::get('lead/edit/{id?}', 'LeadController@edit')->name('lead.edit');
            Route::patch('lead/update', 'LeadController@update')->name('lead.update');
            Route::post('lead/change-status', 'LeadController@change_status')->name('lead.change.status');
        /** lead */

        /** orders */
            Route::any('order', 'OrderController@index')->name('order');
            Route::get('order/view/{id?}', 'OrderController@view')->name('order.view');
        /** orders */

        /** transfer */
            Route::any('transfer', 'TransferController@index')->name('transfer');
            Route::get('transfer/create', 'TransferController@create')->name('transfer.create');
            Route::post('transfer/insert', 'TransferController@insert')->name('transfer.insert');
            Route::get('transfer/accept-check/{id?}', 'TransferController@accept_check')->name('transfer.accept.check');
            Route::get('transfer/inventory-details', 'TransferController@inventory_details')->name('transfer.inventory.details');
            Route::get('transfer/gate-pass/{id?}', 'TransferController@gate_pass')->name('transfer.gate.pass');
            Route::post('transfer/reject', 'TransferController@reject')->name('transfer.reject');
            Route::post('transfer/accept', 'TransferController@accept')->name('transfer.accept');
            Route::get('transfer/transfer-details/{id?}', 'TransferController@transfer_details')->name('transfer.transfer.details');
            Route::post('transfer/change-status', 'TransferController@change_status')->name('transfer.change.status');
        /** transfer */


        /** inventory */
            Route::any('inventory', 'InventoryController@index')->name('inventory');
            Route::get('inventory/create', 'InventoryController@create')->name('inventory.create');
            Route::post('inventory/insert', 'InventoryController@insert')->name('inventory.insert');
            Route::get('inventory/view/{id?}', 'InventoryController@view')->name('inventory.view');
            Route::get('inventory/edit/{id?}', 'InventoryController@edit')->name('inventory.edit');
            Route::patch('inventory/update', 'InventoryController@update')->name('inventory.update');
            Route::post('inventory/change-status', 'InventoryController@change_status')->name('inventory.change.status');
            Route::get('inventory/accept/{id?}', 'InventoryController@accept')->name('inventory.accept');
            Route::post('inventory/accepted', 'InventoryController@accepted')->name('inventory.accepted');
        /** inventory */

        /** Accessory */
            Route::any('accessory', 'AccessoryController@index')->name('accessory');
            Route::get('accessory/create', 'AccessoryController@create')->name('accessory.create');
            Route::post('accessory/insert', 'AccessoryController@insert')->name('accessory.insert');
            Route::get('accessory/view/{id?}', 'AccessoryController@view')->name('accessory.view');
            Route::get('accessory/edit/{id?}', 'AccessoryController@edit')->name('accessory.edit');
            Route::patch('accessory/update', 'AccessoryController@update')->name('accessory.update');
            Route::post('accessory/change_status', 'AccessoryController@change_status')->name('accessory.change.status');
        /** Accessory */

        /** Car Exchange Category */
            Route::any('car_exchange_category', 'CarExchangeCategoryController@index')->name('car_exchange_category');
            Route::get('car_exchange_category/create', 'CarExchangeCategoryController@create')->name('car_exchange_category.create');
            Route::post('car_exchange_category/insert', 'CarExchangeCategoryController@insert')->name('car_exchange_category.insert');
            Route::get('car_exchange_category/view/{id?}', 'CarExchangeCategoryController@view')->name('car_exchange_category.view');
            Route::get('car_exchange_category/edit/{id?}', 'CarExchangeCategoryController@edit')->name('car_exchange_category.edit');
            Route::post('car_exchange_category/update', 'CarExchangeCategoryController@update')->name('car_exchange_category.update');
            Route::post('car_exchange_category/change-status', 'CarExchangeCategoryController@change_status')->name('car_exchange_category.change.status');
        /** Car Exchange Category */
    
        /** Car Exchange Product */
            Route::any('car_exchange_product', 'CarExchangeProductController@index')->name('car_exchange_product');
            Route::get('car_exchange_product/create', 'CarExchangeProductController@create')->name('car_exchange_product.create');
            Route::post('car_exchange_product/insert', 'CarExchangeProductController@insert')->name('car_exchange_product.insert');
            Route::get('car_exchange_product/view/{id?}', 'CarExchangeProductController@view')->name('car_exchange_product.view');
            Route::post('car_exchange_product/update', 'CarExchangeProductController@update')->name('car_exchange_product.update');
            Route::post('car_exchange_product/change-status', 'CarExchangeProductController@change_status')->name('car_exchange_product.change.status');
        /** Car Exchange Product */
    
        /** Car Exchange */
            Route::any('car_exchange', 'CarExchangeController@index')->name('car_exchange');
            Route::post('car_exchange/create', 'CarExchangeController@create')->name('car_exchange.create');
            Route::post('car_exchange/insert', 'CarExchangeController@insert')->name('car_exchange.insert');
            Route::get('car_exchange/view/{id?}', 'CarExchangeController@view')->name('car_exchange.view');
            Route::post('car_exchange/update', 'CarExchangeController@update')->name('car_exchange.update');
            Route::post('car_exchange/change-status', 'CarExchangeController@change_status')->name('car_exchange.change.status');
        /** Car Exchange */

    });
});
