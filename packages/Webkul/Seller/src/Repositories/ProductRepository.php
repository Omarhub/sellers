<?php

namespace Webkul\Seller\Repositories;

use DB;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Seller\Repositories\ProductRepository as SellerProductRepository;

/**
 * Seller Product Reposotory
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
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
     * ProductInventoryRepository object
     *
     * @var array
     */
    protected $productInventoryRepository;

    /**
     * ProductImageRepository object
     *
     * @var Object
     */
    protected $productImageRepository;

    /**
     * sellerProduct Repository object
     *
     * @var Object
     */
    protected $sellerProduct;

    /**
     * SellerRepository object
     *
     * @var Object
     */
    protected $sellerRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository      $attribute
     * @param  Webkul\Product\Repositories\ProductRepository          $productRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository $productInventoryRepository
     * @param  Webkul\Seller\Repositories\ProductImageRepository      $productImageRepository
     * @param  Webkul\Seller\Repositories\SellerRepository            $sellerRepository
     * @param  Illuminate\Container\Container                         $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attribute,
        BaseProductRepository $productRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        SellerRepository $sellerRepository,
        App $app
    )
    {
        // $this->sellerProduct = $sellerProduct;

        $this->attribute = $attribute;

        $this->productRepository = $productRepository;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->productImageRepository = $productImageRepository;

        $this->sellerRepository = $sellerRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Seller\Contracts\Product';
    }

    /**
    * create assign product
    *
    * @return mixed
    */
    public function createAssign(array $data)
    {
        Event::fire('seller.assign-product.create.before');

        $sellerId = $data['seller_id'];

        $sellerProduct = parent::create(array_merge($data, [
                'seller_id' => $sellerId,
            ]));

        if (isset($data['selected_variants'])) {
            foreach ($data['selected_variants'] as $baseVariantId) {
                $sellerChildProduct = parent::create(array_merge($data['variants'][$baseVariantId], [
                        'parent_id' => $sellerProduct->id,
                        'condition' => $sellerProduct->condition,
                        'product_id' => $baseVariantId,
                        'seller_id' => $sellerId,
                    ]));

                $this->productInventoryRepository->saveInventories(array_merge($data['variants'][$baseVariantId], [
                        'vendor_id' => $sellerChildProduct->id
                    ]), $sellerChildProduct->product);
            }
        }

        $this->productInventoryRepository->saveInventories(array_merge($data, [
                'vendor_id' => $sellerProduct->id
            ]), $sellerProduct->product);

        $this->productImageRepository->uploadImages($data, $sellerProduct);

        Event::fire('seller.assign-product.create.after', $sellerProduct);

        return $sellerProduct;
    }

    /**
     * update assign product
     *
     * @param integer $id
     * @return mixed
     */
    public function updateAssign(array $data, $id, $attribute = "id")
    {
        Event::fire('admin.sellers.assign-product.update.before', $id);

        $sellerProduct = $this->find($id);

        parent::update($data, $id);

        $previousBaseVariantIds = $sellerProduct->variants->pluck('product_id');

        if (isset($data['selected_variants'])) {
            foreach ($data['selected_variants'] as $baseVariantId) {
                $variantData = $data['variants'][$baseVariantId];

                if (is_numeric($index = $previousBaseVariantIds->search($baseVariantId))) {
                    $previousBaseVariantIds->forget($index);
                }

                $sellerChildProduct = $this->findOneWhere([
                        'product_id' => $baseVariantId,
                        'seller_id' => $sellerProduct->seller_id,
                    ]);

                if ($sellerChildProduct) {
                    parent::update(array_merge($variantData, [
                            'price' => $variantData['price'],
                            'condition' => $data['condition']
                        ]), $sellerChildProduct->id);

                    $this->productInventoryRepository->saveInventories(array_merge($variantData, [
                            'vendor_id' => $sellerChildProduct->id
                        ]), $sellerChildProduct->product);
                } else {
                    $sellerChildProduct = parent::create(array_merge($variantData, [
                            'parent_id' => $sellerProduct->id,
                            'product_id' => $baseVariantId,
                            'condition' => $sellerProduct->condition,
                            'seller_id' => $sellerProduct->seller->id,
                        ]));

                    $this->productInventoryRepository->saveInventories(array_merge($variantData, [
                            'vendor_id' => $sellerChildProduct->id
                        ]), $sellerChildProduct->product);
                }
            }
        }

        if ($previousBaseVariantIds->count()) {
            $sellerProduct->variants()
                ->whereIn('product_id', $previousVariantIds)
                ->delete();
        }

        $this->productImageRepository->uploadImages($data, $sellerProduct);

        $this->productInventoryRepository->saveInventories(array_merge($data, [
                'vendor_id' => $sellerProduct->id
            ]), $sellerProduct->product);

        Event::fire('admin.sellers.assign-product.update.after', $sellerProduct);

        return $sellerProduct;
    }

    /**
     * Returns the seller products of the product
     *
     * @param Product $product
     * @return Collection
     */
    public function getSellerProducts($product)
    {
        $parentId = [];
        if ($product->product->type == 'configurable') {
            $parent = $this->findWhere([
                'product_id' => $product->id,
            ]);

            foreach ($parent as $child) {
                $parentId[] = $child->id;
            }

            return $this->findWhereIn('parent_id', $parentId);
        } else {
            return $this->findWhere([
                'product_id' => $product->id,
            ]);
        }
    }

    /**
     * Returns the seller products of the product
     *
     * @param Product $product
     * @return Collection
     */
    public function getsellerProductCount($product)
    {
        if ($product->type != 'configurable') {
            $sellerProducts = $this->findWhere(['product_id' => $product->id]);

            if ($sellerProducts) {
                return true;
            }
        } else if($product->type == 'configurable') {
            $sellerProducts = $this->findWhere(['product_id' => $product->id]);

            if ($sellerProducts) {
                return true;
            }
        } else {
            return false;
        }


    }

    /**
     * Returns the seller Name of the product
     *
     * @param Product $product
     * @return Collection
     */
    public function getSellerName($sellerId)
    {
        $sellerName = app('Webkul\Seller\Repositories\SellerRepository');

        return $sellerName->findWhere([
            'id' => $sellerId,
        ])->first();
    }

    /**
     * Returns seller by product
     *
     * @param integer $productId
     * @return boolean
     */
    public function getSellerByProductId($productId)
    {
        $product = parent::findOneWhere([
                'product_id' => $productId,
            ]);

        if (! $product) {
            return;
        }
        return $product->seller;
    }

    /**
     * Return The productAttribute For Compare Product
     *
     * @param Product $product
     * @return Collection
     */
    public function getFamilyAttribute($product)
    {
        $productId = $product->product_id;

        $familyAttribute = app('Webkul\Product\Repositories\ProductRepository');

        $compareProducts = app('Webkul\Product\Repositories\ProductFlatRepository');

        $products = $familyAttribute->findWhere(['id' => $productId])->first();

        $attributeId = $products->attribute_family_id;

        $comparableProducts = $familyAttribute->findWhere(['attribute_family_id' => $attributeId]);

        foreach ($comparableProducts as $compareProductss) {

            $compareProduct = $compareProducts->findWhere(['product_id' => $compareProductss->id])->first();

            $baseProduct = $compareProduct->product()->first();

            if ($baseProduct->type == 'simple' && $baseProduct->parent_id == null) {

                $attributeCompareProduct[] = $compareProduct;
            }else if($baseProduct->type == 'configurable') {
                $attributeCompareProduct[] = $compareProduct;
            }
        }

        if (isset($attributeCompareProduct))
        {
            if (count($attributeCompareProduct) > 4) {
                $attributeCompareProduct = array_random($attributeCompareProduct, 4);
            };
        }

        return $attributeCompareProduct;
    }

    /**
     *return $attributeCompareProduct;
     * update and create assign product
     *
     * @param array $data
     * @param integer $id
     * @return mixed
     */
    public function createupdateAssignProduct(array $data, $id)
    {
        if (isset($data['sellers'])) {
            foreach ($data['sellers'] as $seller) {
                $assignProductData['_token'] = $data['_token'];
                $assignProductData['_method'] = $data['_method'];
                $assignProductData['description'] = $data['description'];
                $assignProductData['images'] = $data['images'];

                if (isset($data['variants'])) {
                    foreach ($data['variants'] as $key => $variant) {
                        $assignProductData['selected_variants'][] = (string) $key;
                        $assignProductData['variants'][$key]['inventories'] = $variant['inventories'];
                        $assignProductData['variants'][$key]['price'] = $variant['price'];
                    }
                } else {
                    $assignProductData['inventories'] = $data['inventories'];
                    $assignProductData['price'] = $data['price'];
                }

                $product = $this->findOneWhere([
                    'product_id' => $id,
                    'seller_id'  => $seller
                ]);

                if ($product) {
                    $this->updateAssign($assignProductData, $product->id);
                } else {
                    $assignProductData['product_id'] = $id;
                    $assignProductData['seller_id'] = $seller;

                    $this->createAssign($assignProductData);
                }
            }
        }
    }

        /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        Event::fire('seller.product.delete.before', $id);

        parent::delete($id);

        $inventory = $this->productInventoryRepository->findOneWhere([
            'vendor_id' => $id
        ]);

        if ($inventory) {
            $this->productInventoryRepository->delete($inventory->id);
        }

        Event::fire('seller.product.delete.after', $id);
    }

    /**
     * Returns the seller product's minimal price
     *
     * @param Product $product
     * @return float
     */
    public function getSellerMinimalPrice($product)
    {
        static $price = [];

        if ($product->type == 'configurable') {

            $sellerProducts = $this->findWhere(['product_id' => $product->id]);

            foreach ($sellerProducts as $sellerProduct) {

                if ($this->findWhere(['parent_id' => $sellerProduct->id]))
                {
                    $varients = $this->findWhere(['parent_id' => $sellerProduct->id]);

                    foreach ($varients as $varient) {
                        $productPrice[] = $varient->price;
                    }

                } else if ($this->findWhere(['parent_id' => ''])) {

                }

            }

            $minimalPrice = min($productPrice);

            return $minimalPrice;
        } else {

            $sellerProducts = $this->findWhere(['product_id' => $product->id ,'parent_id' => null]);

            if (count($sellerProducts) > 0) {
                foreach ($sellerProducts as $sellerProduct) {
                    $productPrice[] = $sellerProduct->price;
                }

                $minimalPrice = min($productPrice);

                return $minimalPrice;
            }
        }
    }

    /**
     *return $configurableProduct From Varient;
     * get the main product from the varient product
     *
     * @param array $productId
     * @return mixed
     */
    public function getconfigurableProduct($productId)
    {
        $products = app('Webkul\Product\Repositories\ProductFlatRepository');
        $mainProduct = $products->findOneWhere(['id'=>$productId]);

        return $mainProduct;
    }

    /**
     * @param Product $product
     * @return boolean
     */
    public function sellerProduct($productId)
    {
        $sellerProduct = $this->findOneByField('id', $productId);

        return $sellerProduct;

    }

}