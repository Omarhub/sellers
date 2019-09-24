<?php
namespace Webkul\Seller\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Seller\Contracts\Seller as SellerContract;

class Seller extends Model implements SellerContract
{
    protected $table = 'sellers';

    protected $fillable = ['first_name' ,'last_name', 'email'];
}
