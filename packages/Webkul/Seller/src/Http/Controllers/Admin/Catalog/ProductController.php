<?php

namespace Webkul\Seller\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use Webkul\Seller\Http\Controllers\Admin\Controller;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;
use Webkul\Seller\Repositories\ProductRepository as SellerProduct;
use Webkul\Seller\Repositories\SellerRepository as Seller;
use Illuminate\Support\Facades\Event;
use Webkul\Seller\Http\Requests\ProductForm;
use Webkul\Product\Repositories\ProductAttributeValueRepository as ProductAttributeValue;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Illuminate\Support\Facades\Storage;

/**
 * Catalog Product Controller
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * SellerRepository object
     *
     * @var array
     */
    protected $seller;

    /**
     * InventorySourceRepository object
     *
     * @var array
     */
    protected $inventorySource;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $sellerProduct;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Seller\Repositories\SellerRepository  $seller
     * @param  Webkul\Inventory\Repositories\InventorySourceRepository $inventorySource
     * @param  Webkul\Product\Repositories\ProductRepository $product
     * @param  Webkul\Seller\Repositories\ProductRepository $sellerProduct
     * @param  Webkul\Seller\Repositories\SellerRepository $seler
     * @return void
     */
    public function __construct(
        Seller $seller,
        InventorySource $inventorySource,
        Product $product,
        SellerProduct $sellerProduct,
        AttributeFamily $attributeFamily,
        Category $category,
        ProductAttributeValue $productAttributeValue
    )
    {
        $this->seller = $seller;

        $this->product = $product;

        $this->sellerProduct = $sellerProduct;

        $this->attributeFamily = $attributeFamily;

        $this->category = $category;

        $this->inventorySource = $inventorySource;

        $this->productAttributeValue = $productAttributeValue;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $families = $this->attributeFamily->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'configurableFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if (!request()->get('family') && request()->input('type') == 'configurable' && request()->input('sku') != '') {
            return redirect(url()->current() . '?family=' . request()->input('attribute_family_id') . '&sku=' . request()->input('sku'));
        }

        if (request()->input('type') == 'configurable' && (! request()->has('super_attributes') || ! count(request()->get('super_attributes')))) {
            session()->flash('error', trans('admin::app.catalog.products.configurable-error'));

            return back();
        }

        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug]
        ]);

        $product = $this->product->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Product']));

        return redirect()->route($this->_config['redirect'], ['id' => $product->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->product->with(['variants', 'variants.inventories'])->findOrFail($id);

        $categories = $this->category->getCategoryTree();

        $inventorySources = $this->inventorySource->all();

        return view($this->_config['view'], compact('product', 'categories', 'inventorySources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Product\Http\Requests\ProductForm $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductForm $request, $id)
    {
        $sellerProduct = $this->sellerProduct->findWhere(['product_id' => $id]);

        if (count($sellerProduct) > 0)
        {
            $product = $this->product->update(request()->all(), $id);

            session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Product']));
        } else {

            session()->flash('error', trans('seller::app.admin.seller.products.no-seller'));

            return back();
        }



        return redirect()->route($this->_config['redirect']);
    }
}
