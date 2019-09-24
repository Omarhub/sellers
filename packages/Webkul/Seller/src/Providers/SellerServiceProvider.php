<?php

namespace Webkul\Seller\Providers;

use Illuminate\Support\ServiceProvider;

class SellerServiceProvider extends ServiceProvider
{
     /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/front-routes.php';

        include __DIR__ . '/../Http/admin-routes.php';

        $this->app->register(ModuleServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'seller');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'seller');

        $this->overrideModels();

        $this->overrideViews();
    }

    /**
     * Register Services
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package Config
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__ ) . '/Config/acl.php', 'acl'
        );
    }

    /**
     * Override the existing models
     */
    public function overrideModels()
    {
        $this->app->concord->registerModel(
            \Webkul\Customer\Contracts\CustomerAddress::class, \Webkul\Seller\Models\CustomerAddress::class
        );

        $this->app->concord->registerModel(
            \Webkul\Checkout\Contracts\CartAddress::class, \Webkul\Seller\Models\CartAddress::class
        );

        $this->app->concord->registerModel(
            \Webkul\Sales\Contracts\OrderAddress::class, \Webkul\Seller\Models\OrderAddress::class
        );

        $this->app->concord->registerModel(
            \Webkul\Checkout\Contracts\CartItem::class, \Webkul\Checkout\Models\CartItem::class
        );
    }

     /**
     * Override the existing views
     */
    public function overrideViews()
    {
        $this->publishes([
            dirname(__DIR__) . '/Resources/views/shop/products/view.blade.php' => base_path('resources/views/vendor/shop/products/view.blade.php')
        ]);

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/customers/account/address/create.blade.php' => resource_path('views/vendor/shop/customers/account/address/create.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/customers/account/address/index.blade.php' => resource_path('views/vendor/shop/customers/account/address/index.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/customers/account/address/edit.blade.php' => resource_path('views/vendor/shop/customers/account/address/edit.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/catalog/products/edit.blade.php' => resource_path('views/vendor/admin/catalog/products/edit.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/checkout/onepage/customer-info.blade.php' => resource_path('views/vendor/shop/checkout/onepage/customer-info.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/checkout/onepage/review.blade.php' => resource_path('views/vendor/shop/checkout/onepage/review.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/sales/address.blade.php' => resource_path('views/vendor/admin/sales/address.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/checkout/onepage.blade.php' => resource_path('views/vendor/shop/checkout/onepage.blade.php'),
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/shop/products/add-buttons.blade.php' => base_path('resources/views/vendor/shop/products/add-buttons.blade.php')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/shop/products/price.blade.php' => base_path('resources/views/vendor/shop/products/price.blade.php')
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/catalog/products/index.blade.php' => resource_path('views/vendor/admin/catalog/products/index.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/sales/invoices/view.blade.php' => resource_path('views/vendor/admin/sales/invoices/view.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/sales/orders/view.blade.php' => resource_path('views/vendor/admin/sales/orders/view.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/sales/shipments/view.blade.php' => resource_path('views/vendor/admin/sales/shipments/view.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/sales/shipments/create.blade.php' => resource_path('views/vendor/admin/sales/shipments/create.blade.php'),
        ]);
    }
}