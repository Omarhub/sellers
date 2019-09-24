<?php

namespace Webkul\Seller\Models;

use Webkul\Checkout\Models\CartAddress as CartAddressBaseModel;

class CartAddress extends CartAddressBaseModel
{
    protected $fillable = ['first_name', 'last_name', 'email', 'address1', 'city', 'state', 'postcode',  'country', 'phone', 'address_type', 'cart_id', 'area', 'block', 'street', 'avenue', 'building', 'flat', 'floor', 'direction'];
}