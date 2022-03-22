<?php
use Lmate\Customer\Http\Controllers\CustomerController;

Route::group(['middleware' => ['web']], function() {    
    Route::resource('customers', CustomerController::class);
    Route::match(['get','post', 'head'], 'customers_search',[CustomerController::class, 'searchFilter'])->name('customers.search');
    Route::match(['get'], '/customer_export',[CustomerController::class, 'get_customer_data'])->name('customers.export');
    Route::match(['post'], '/customer_status_update',[CustomerController::class, 'statusUpdate'])->name('customers.status_update');

    Route::post('api/fetch-states', [CustomerController::class, 'fetchStatesOrCity']);
    Route::post('api/fetch-cities', [CustomerController::class, 'fetchStatesOrCity']);
    Route::get('api/search-cities',[CustomerController::class, 'searchCities'])->name('customers.searchCities');
    Route::post('api/update-status',[CustomerController::class, 'updateCustomerStatus'])->name('customers.updateStatus');
});
