<?php

namespace Webkul\Seller\Models;

use Webkul\Sales\Models\Order as OrderBaseModel;
use Webkul\Seller\Models\OrderAddress as OrderAddress;

class Order extends OrderBaseModel
{
    /**
     * Get the addresses for the order.
     */
    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }
}