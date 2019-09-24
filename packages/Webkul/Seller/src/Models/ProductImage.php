<?php

namespace Webkul\Seller\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Seller\Contracts\ProductImage as ProductImageContract;

class ProductImage extends Model implements ProductImageContract
{
    protected $table = 'seller_product_images';

    public $timestamps = false;

    protected $fillable = ['path', 'seller_product_id'];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass(), 'seller_product_id');
    }

    /**
     * Get image url for the product image.
     */
    public function url()
    {
        return Storage::url($this->path);
    }

}