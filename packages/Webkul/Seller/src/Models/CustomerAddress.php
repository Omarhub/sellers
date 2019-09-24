<?php

namespace Webkul\Seller\Models;

use Webkul\Customer\Models\CustomerAddress as CustomerAddressBaseModel;


class CustomerAddress extends CustomerAddressBaseModel
{
    protected $fillable = ['customer_id' ,'address1', 'country', 'state', 'city', 'postcode', 'phone', 'default_address', 'area', 'block', 'street', 'avenue', 'building', 'flat', 'floor'];
}