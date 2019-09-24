<?php

namespace Webkul\Seller\Models;

use Webkul\Sales\Models\OrderAddress as OrderAddressBaseModel;

class OrderAddress extends OrderAddressBaseModel
{
    protected $table = 'order_address';
}