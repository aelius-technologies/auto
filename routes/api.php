<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api', 'namespace' => 'API'], function () {
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', 'AuthController@logout');

        /** permission */
            Route::get('permission', 'PermissionController@index');
            Route::post('permission/insert', 'PermissionController@insert');
            Route::get('permission/view/{id?}', 'PermissionController@view');
            Route::post('permission/update', 'PermissionController@update');
            Route::post('permission/change-status', 'PermissionController@change_status');
        /** permission */

        /** Roles */
            Route::get('role', 'RolesController@index');
            Route::post('role/insert', 'RolesController@insert');
            Route::get('role/view/{id?}', 'RolesController@view');
            Route::post('role/update', 'RolesController@update');
            Route::post('role/change-status', 'RolesController@change_status');
        /** Roles */

        /** Roles Has Permission */
            Route::get('access', 'AccessController@index');
            Route::post('access/insert', 'AccessController@insert');
            Route::get('access/view/{id?}', 'AccessController@view');
            Route::post('access/update', 'AccessController@update');
        /** Roles Has Permission */
       
        /** users */
            Route::get('users', 'UsersController@index');
            Route::post('users/insert', 'UsersController@insert');
            Route::get('users/view/{id?}', 'UsersController@view');
            Route::post('users/update', 'UsersController@update');
            Route::post('users/change-status', 'UsersController@change_status');
            Route::post('users/import','UsersController@import');
            Route::post('users/export','UsersController@export');
        /** users */
       
        /** Branches */
            Route::get('branches', 'BranchController@index');
            Route::post('branches/insert', 'BranchController@insert');
            Route::get('branches/view/{id?}', 'BranchController@view');
            Route::post('branches/update', 'BranchController@update');
            Route::post('branches/change-status', 'BranchController@change_status');
        /** Branches */
       
        /** Category */
            Route::get('categories', 'CategoryController@index');
            Route::post('categories/insert', 'CategoryController@insert');
            Route::get('categories/view/{id?}', 'CategoryController@view');
            Route::post('categories/update', 'CategoryController@update');
            Route::post('categories/change-status', 'CategoryController@change_status');
        /** Category */
     
        /** Product */
            Route::get('products', 'ProductController@index');
            Route::post('products/insert', 'ProductController@insert');
            Route::get('products/view/{id?}', 'ProductController@view');
            Route::post('products/update', 'ProductController@update');
            Route::post('products/change-status', 'ProductController@change_status');
        /** Product */
        
        /** Taxes */
            Route::get('taxes', 'TaxController@index');
            Route::post('taxes/insert', 'TaxController@insert');
            Route::get('taxes/view/{id?}', 'TaxController@view');
            Route::post('taxes/update', 'TaxController@update');
            Route::post('taxes/change-status', 'TaxController@change_status');
        /** Taxes */
        
        /** Insurance */
            Route::get('insurance', 'InsuranceController@index');
            Route::post('insurance/insert', 'InsuranceController@insert');
            Route::get('insurance/view/{id?}', 'InsuranceController@view');
            Route::post('insurance/update', 'InsuranceController@update');
            Route::post('insurance/change-status', 'InsuranceController@change_status');
        /** Insurance */
       
        /** Accessory */
            Route::get('accessories', 'AccessoryController@index');
            Route::post('accessories/insert', 'AccessoryController@insert');
            Route::get('accessories/view/{id?}', 'AccessoryController@view');
            Route::post('accessories/update', 'AccessoryController@update');
            Route::post('accessories/change-status', 'AccessoryController@change_status');
        /** Accessory */
       
        /** Extand Warranty */
            Route::get('extand_warranty', 'ExtandWarrantyController@index');
            Route::post('extand_warranty/insert', 'ExtandWarrantyController@insert');
            Route::get('extand_warranty/view/{id?}', 'ExtandWarrantyController@view');
            Route::post('extand_warranty/update', 'ExtandWarrantyController@update');
            Route::post('extand_warranty/change-status', 'ExtandWarrantyController@change_status');
        /** Extand Warranty */
       
        /** FastTag */
            Route::get('fasttag', 'FasttagController@index');
            Route::post('fasttag/insert', 'FasttagController@insert');
            Route::get('fasttag/view/{id?}', 'FasttagController@view');
            Route::post('fasttag/update', 'FasttagController@update');
            Route::post('fasttag/change-status', 'FasttagController@change_status');
        /** FastTag */
       
        /** Car Exchange Category */
            Route::get('car_exchange_category', 'CarExchangeCategoryController@index');
            Route::post('car_exchange_category/insert', 'CarExchangeCategoryController@insert');
            Route::get('car_exchange_category/view/{id?}', 'CarExchangeCategoryController@view');
            Route::post('car_exchange_category/update', 'CarExchangeCategoryController@update');
            Route::post('car_exchange_category/change-status', 'CarExchangeCategoryController@change_status');
        /** Car Exchange Category */
       
        /** Car Exchange Product */
            Route::get('car_exchange_product', 'CarExchangeProductController@index');
            Route::post('car_exchange_product/insert', 'CarExchangeProductController@insert');
            Route::get('car_exchange_product/view/{id?}', 'CarExchangeProductController@view');
            Route::post('car_exchange_product/update', 'CarExchangeProductController@update');
            Route::post('car_exchange_product/change-status', 'CarExchangeProductController@change_status');
        /** Car Exchange Product */
       
        /** Car Exchange */
            Route::get('car_exchange', 'CarExchangeController@index');
            Route::get('car_exchange_get_category', 'CarExchangeController@get_category');
            Route::post('car_exchange_get_model', 'CarExchangeController@get_model');
            Route::post('car_exchange/insert', 'CarExchangeController@insert');
            Route::get('car_exchange/view/{id?}', 'CarExchangeController@view');
            Route::post('car_exchange/update', 'CarExchangeController@update');
            Route::post('car_exchange/change-status', 'CarExchangeController@change_status');
        /** Car Exchange */
       
        /** Lead */
            Route::get('lead', 'LeadController@index');
            Route::post('lead/insert', 'LeadController@insert');
            Route::get('lead/view/{id?}', 'LeadController@view');
            Route::post('lead/update', 'LeadController@update');
            Route::post('lead/change-status', 'LeadController@change_status');
        /** Lead */
        
        /** Finance */
            Route::get('finance', 'FinanceController@index');
            Route::post('finance/insert', 'FinanceController@insert');
            Route::get('finance/view/{id?}', 'FinanceController@view');
            Route::post('finance/update', 'FinanceController@update');
            Route::post('finance/change-status', 'FinanceController@change_status');
        /** Finance */
       
        /** Special Registration Number */
            Route::get('special_registration_number', 'SpecialRegistrationNumberController@index');
            Route::post('special_registration_number/insert', 'SpecialRegistrationNumberController@insert');
            Route::get('special_registration_number/view/{id?}', 'SpecialRegistrationNumberController@view');
            Route::post('special_registration_number/update', 'SpecialRegistrationNumberController@update');
            Route::post('special_registration_number/change-status', 'SpecialRegistrationNumberController@change_status');
        /** Special Registration Number */
       
        /** Department */
            Route::get('department', 'DepartmentController@index');
            Route::post('department/insert', 'DepartmentController@insert');
            Route::get('department/view/{id?}', 'DepartmentController@view');
            Route::post('department/update', 'DepartmentController@update');
            Route::post('department/change-status', 'DepartmentController@change_status');
        /** Department */
       
        /** Order Booking Fourm (OBF) */
            Route::get('obf', 'ObfController@index');
            Route::get('obf/get_obf_master_data', 'ObfController@get_obf_master_data');
            Route::post('obf/insert', 'ObfController@insert');
            Route::get('obf/view/{id?}', 'ObfController@view');
            Route::post('obf/update', 'ObfController@update');
            Route::post('obf/change-status', 'ObfController@change_status');
        /** Order Booking Fourm (OBF) */
        
        /** Allocation */
            Route::get('allocation', 'AllocationController@index');
            Route::post('allocation/insert', 'AllocationController@insert');
            Route::get('allocation/view/{id?}', 'AllocationController@view');
            Route::post('allocation/update', 'AllocationController@update');
            Route::post('allocation/change-status', 'AllocationController@change_status');
        /** Allocation */
       
        /** Approval */
            Route::get('approval', 'ApprovalController@index');
            Route::get('approval/view/{id?}', 'ApprovalController@view');
            Route::post('approval/change-status', 'ApprovalController@change_status');
        /** Approval */

        /** Order */
            Route::get('orders', 'OrderController@index');
            Route::get('orders/view/{id?}', 'OrderController@view');
            Route::post('orders/change-status', 'OrderController@change_status');
            Route::post('orders/get_gate_pass', 'OrderController@get_gate_pass');
            Route::post('orders/check_inventory', 'OrderController@check_inventory');
            Route::post('orders/change_car_status', 'OrderController@change_car_status');
            /** Order */
            
            /** Location Wise Transfer */
            Route::get('transfer', 'TransferController@index');  
            Route::post('transfer/send_transfer_request', 'TransferController@send_transfer_request');
            Route::get('transfer/view/{id?}', 'TransferController@view');
            Route::post('transfer/change-status', 'TransferController@change_status');
            Route::post('transfer/get_gate_pass', 'TransferController@get_gate_pass');
        /** Location Wise Transfer */

        /** Cash Receipt */
            Route::get('cash_receipt', 'CashReceiptController@index');  
            Route::get('cash_receipt/view/{id?}', 'CashReceiptController@view');
            Route::post('cash_receipt/change-status', 'CashReceiptController@change_status');
            Route::post('cash_receipt/generate_cash_receipt', 'CashReceiptController@generate_cash_receipt');
        /** Cash Receipt */

        
        /** Inventory */
            Route::get('inventory', 'InventoryController@index');
            Route::post('inventory/insert', 'InventoryController@insert');
            Route::get('inventory/view/{id?}', 'InventoryController@view');
            Route::post('inventory/update', 'InventoryController@update');
            Route::post('inventory/change-status', 'InventoryController@change_status');
            Route::post('inventory/get_users', 'InventoryController@get_users');
            Route::post('inventory/assign_car', 'InventoryController@assign_car');
        /** Inventory */
        
        /** Account Approval */
            Route::post('account_approval/change-status', 'AccountApprovalController@change_status');
        /** Account Approval */

        
    });
});

Route::get('/unauthenticated', function () {
    return response()->json(['status' => 201, 'message' => 'Unauthorized Access']);
})->name('api.unauthenticated');

Route::get("{path}", function(){ return response()->json(['status' => 500, 'message' => 'Bad request']); })->where('path', '.+');
