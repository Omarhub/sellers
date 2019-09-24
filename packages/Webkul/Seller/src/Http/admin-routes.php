<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin/sellers')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            //Seller Routs Start From Here
            Route::get('/view', 'Webkul\Seller\Http\Controllers\Admin\SellerController@index')->defaults('_config', [
                'view' => 'seller::admin.sellers.seller.index'
            ])->name('admin.seller.index');

            //Create Seller
            Route::get('/sellers/create', 'Webkul\Seller\Http\Controllers\Admin\SellerController@create')->defaults('_config', [
                'view' => 'seller::admin.sellers.seller.create'
            ])->name('admin.seller.create');

            //Store Seller
            Route::post('/sellers/create', 'Webkul\Seller\Http\Controllers\Admin\SellerController@store')->defaults('_config', [
                'redirect' => 'admin.seller.index'
            ])->name('admin.seller.store');

            //Edit Seller
            Route::get('/sellers/edit/{id}', 'Webkul\Seller\Http\Controllers\Admin\SellerController@edit')->defaults('_config', [
                'view' => 'seller::admin.sellers.seller.edit'
            ])->name('sellers.admin.seller.edit');

            //update Seller
            Route::post('/sellers/edit/{id}', 'Webkul\Seller\Http\Controllers\Admin\SellerController@update')->defaults('_config', [
                'redirect' => 'admin.seller.index'
            ])->name('sellers.admin.seller.update');

            //Delete Seller
            Route::post('/sellers/delete/{id}', 'Webkul\Seller\Http\Controllers\Admin\SellerController@destroy')->defaults('_config', [
                'redirect' => 'admin.seller.index'
            ])->name('sellers.admin.seller.delete');

            //Mass Delete Seller
            Route::post('/sellers/mass-delete', 'Webkul\Seller\Http\Controllers\Admin\SellerController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.seller.index'
            ])->name('sellers.admin.seller.mass-delete');

            //Add Seller Products
            Route::get('/product', 'Webkul\Seller\Http\Controllers\Admin\ProductController@index')->defaults('_config', [
                'view' => 'seller::admin.sellers.products.index'
            ])->name('admin.seller.products.index');

            // Seller Product Search
            Route::get('/products/search/id', 'Webkul\Seller\Http\Controllers\Admin\ProductController@show')->defaults('_config', [
                'view' => 'seller::admin.sellers.products.search'
            ])->name('admin.seller.products.search');

            // Create Seller Product
            Route::get('/product/create/id/{id}', 'Webkul\Seller\Http\Controllers\Admin\ProductController@show')->defaults('_config', [
                'view' => 'seller::admin.sellers.products.search'
            ])->name('admin.seller.products.create');

            //Assign Product To Seller
            Route::get('/product/assign/{id?}/{product_id?}', 'Webkul\Seller\Http\Controllers\Admin\ProductController@createProduct')->defaults('_config', [
                'view' => 'seller::admin.sellers.products.create'
            ])->name('admin.seller.products.assign.create');

            //Store the Seller product
            Route::post('/product/assign/{id?}/{product_id?}', 'Webkul\Seller\Http\Controllers\Admin\ProductController@store')->defaults('_config', [
                'redirect' => 'admin.seller.products.index'
            ])->name('admin.seller.products.assign-store');

            //Edit assign product
            Route::get('/products/assign/edit/{id}', 'Webkul\Seller\Http\Controllers\Admin\ProductController@edit')->defaults('_config', [
                'view' => 'seller::admin.sellers.products.edit'
            ])->name('admin.seller.products.assign.edit');

            //Update Seller Product
            Route::put('/products/assign/edit/{id}', 'Webkul\Seller\Http\Controllers\Admin\ProductController@update')->defaults('_config', [
                'redirect' => 'admin.seller.products.index'
            ])->name('admin.seller.products.assign.update');

              //product delete
            Route::post('/products/delete/{id}', 'Webkul\Seller\Http\Controllers\Admin\ProductController@destroy')->name('seller.catalog.products.delete');

            //product massdelete
            Route::post('products/massdelete', 'Webkul\Seller\Http\Controllers\Admin\ProductController@massDestroy')->defaults('_config', [
                  'redirect' => 'admin.seller.products.index'
              ])->name('seller.catalog.products.massdelete');
        });

        
        Route::post('assign/product', 'Webkul\Seller\Http\Controllers\Admin\ProductController@createAssignProduct')->name('admin.seller.assign.product');
    });
});