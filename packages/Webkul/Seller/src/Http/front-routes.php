<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    //Cart Items Add
    Route::post('checkout/cart/add/seller/product/{id}', 'Webkul\Seller\Http\Controllers\Shop\CartController@add')->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('cart.add.seller.product');

    Route::prefix('customer')->group(function () {
        Route::group(['middleware' => ['customer']], function () {
            Route::prefix('account')->group(function () {
                //Customer Address Create Form Store
                Route::post('addresses/create', 'Webkul\Seller\Http\Controllers\Shop\AddressController@store')->defaults('_config', [
                    'view' => 'shop::customers.account.address.address',
                    'redirect' => 'customer.address.index'
                ])->name('customer.address.create');

                //Customer Address Edit Form Store
                Route::put('addresses/edit/{id}', 'Webkul\Seller\Http\Controllers\Shop\AddressController@update')->defaults('_config', [
                    'redirect' => 'customer.address.index'
                ])->name('customer.address.edit');
            });
        });

         //Checkout Save Address Form Store
        Route::post('/checkout/save-address', 'Webkul\Seller\Http\Controllers\Shop\OnepageController@saveAddress')->name('shop.checkout.save-address');
        });

        //Checkout Save Order
        // Route::post('/checkout/save-order', 'Webkul\Seller\Http\Controllers\Shop\OnepageController@saveOrder')->name('shop.checkout.save-order');
});