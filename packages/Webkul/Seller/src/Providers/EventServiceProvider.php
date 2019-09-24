<?php

namespace Webkul\Seller\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(['bagisto.shop.checkout.cart.item.name.after', 'bagisto.shop.checkout.cart-mini.item.name.after', 'bagisto.shop.checkout.name.after'], function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('seller::shop.checkout.cart.seller-info');
        });

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('seller::admin.layouts.style');
        });

        Event::listen('checkout.cart.add.before', 'Webkul\Seller\Listeners\Cart@cartItemAddBefore');

        Event::listen('checkout.cart.add.after', 'Webkul\Seller\Listeners\Cart@cartItemAddAfter');
    }
}