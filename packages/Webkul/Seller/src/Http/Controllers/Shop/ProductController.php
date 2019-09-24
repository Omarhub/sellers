<?php

namespace Webkul\Seller\Http\Controllers\Shop;

use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class ProductController extends Controller
{
    /**
     * ProductRepository object
     *
     * @var array
    */
    protected $baseProduct;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository     $baseProduct
     * @return void
     */
    public function __construct(BaseProductRepository $baseProduct)
    {
        $this->baseProduct = $baseProduct;

        $this->_config = request('_config');
    }


    /**
     * Product offers by sellers
     *
     * @param  integer $id
     * @return Mixed
     */
    public function offers($id)
    {
        $product = $this->baseProduct->findOrFail($id);

        if ($product->type == 'configurable') {
            session()->flash('error', trans('shop::app.checkout.cart.integrity.missing_options'));

            return redirect()->route('shop.products.index', ['slug' => $product->url_key]);
        }

        return view($this->_config['view'], compact('product'));
    }
}
