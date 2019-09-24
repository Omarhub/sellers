<?php

namespace Webkul\Seller\Models;

use Illuminate\Database\Eloquent\Model;

use Webkul\Product\Models\ProductOrderedInventory as ProductOrderedInventoryBaseModel;

class ProductOrderedInventory extends ProductOrderedInventoryBaseModel
{
    protected $fillable = ['qty', 'product_id', 'channel_id', 'vendor_id'];
}