<?php
namespace Webkul\Seller\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Seller\Contracts\Product as ProductContract;
use Webkul\Product\Models\Product as BaseProduct;

class Product extends Model implements ProductContract
{
    protected $table = 'seller_products';

    protected $fillable = ['condition', 'description', 'price', 'seller_id', 'parent_id', 'product_id', 'warranty', 'is_approved'];

    /**
     * Get the product that belongs to the product.
     */
    public function product()
    {
        return $this->belongsTo(BaseProduct::class);
    }

    /**
     * Get the product that belongs to the seller.
     */
    public function seller()
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'seller_id');
    }

    /**
     * Get the product variants that owns the product.
     */
    public function variants()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the product that owns the product.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * The images that belong to the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImageProxy::modelClass(), 'seller_product_id');
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        $total = 0;

        $channelInventorySourceIds = core()->getCurrentChannel()
                ->inventory_sources()
                ->where('status', 1)
                ->pluck('id');

        foreach ($this->product->inventories as $inventory) {
            if (is_numeric($index = $channelInventorySourceIds->search($inventory->inventory_source_id)) && $this->id == $inventory->vendor_id) {
                $total += $inventory->qty;
                $vendor_id = $inventory->vendor_id;
            }
        }

        if (! $total) {
            return false;
        }

        $orderedInventory = $this->product->ordered_inventories()
                ->where('channel_id', core()->getCurrentChannel()->id)
                ->where('vendor_id', $vendor_id)
                ->first();

        if ($orderedInventory) {
            $total -= $orderedInventory->qty;
        }

        return $qty <= $total ? true : false;
    }
    public function getCurrentQuantity()
    {
        $total = 0;

        $channelInventorySourceIds = core()->getCurrentChannel()
                ->inventory_sources()
                ->where('status', 1)
                ->pluck('id');

        foreach ($this->product->inventories as $inventory) {
            if (is_numeric($index = $channelInventorySourceIds->search($inventory->inventory_source_id)) && $this->id == $inventory->vendor_id) {
                $total += $inventory->qty;
                $vendor_id = $inventory->vendor_id;
            }
        }

        if (! $total) {
            return $total;
        }

        $orderedInventory = $this->product->ordered_inventories()
                ->where('channel_id', core()->getCurrentChannel()->id)
                ->where('vendor_id', $vendor_id)
                ->first();

        if ($orderedInventory) {
            $total -= $orderedInventory->qty;
        }

        return $total;
    }
}
