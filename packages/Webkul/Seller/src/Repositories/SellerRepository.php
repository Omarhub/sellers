<?php

namespace Webkul\Seller\Repositories;

use DB;
use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

/**
 * Seller Repository
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SellerRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository      $attribute
     * @param  Webkul\Product\Repositories\ProductRepository          $productRepository
     * @param  Illuminate\Container\Container                         $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attribute,
        BaseProductRepository $productRepository,
        App $app
    )
    {
        $this->attribute = $attribute;

        $this->productRepository = $productRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Seller\Contracts\Seller';
    }


    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchProducts($term)
    {
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($term) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                return $query->distinct()
                        ->addSelect('product_flat.*')
                        ->where('product_flat.status', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key')
                        ->where('product_flat.name', 'like', '%' . $term . '%')
                        ->orderBy('product_id', 'desc');
            })->paginate(16);
        return $results;
    }
}