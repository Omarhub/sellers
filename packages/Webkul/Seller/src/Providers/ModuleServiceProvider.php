<?php

namespace Webkul\Seller\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Seller\Models\Seller::class,
        \Webkul\Seller\Models\Product::class,
        \Webkul\Seller\Models\ProductImage::class,
    ];
}