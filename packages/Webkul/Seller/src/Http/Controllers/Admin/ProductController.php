<?php

namespace Webkul\Seller\Http\Controllers\Admin;

use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;
use Webkul\Seller\Repositories\ProductRepository as SellerProduct;
use Webkul\Seller\Repositories\SellerRepository as Seller;
/**
 * Seller's Product Controller
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
        SellerProduct $sellerProduct
    )
    {
        $this->seller = $seller;

        $this->inventorySource = $inventorySource;

        $this->product = $product;

        $this->sellerProduct = $sellerProduct;

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
     * display for create the assignProducts
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * display the search products
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sellerId = $id;

        if (request()->ajax()) {
            $results = [];

            $sellerId = request()->input('sellerId');
            foreach ($this->seller->searchProducts(request()->input('query')) as $row) {
                $results[] = [
                        'id' => $row->product_id,
                        'sku' => $row->sku,
                        'name' => $row->name,
                        'price' => core()->convertPrice($row->price),
                        'formated_price' => core()->currency(core()->convertPrice($row->price)),
                        'base_image' => $row->product->base_image_url,
                        'seller_id' => $sellerId
                    ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view'])->with('sellerId',$sellerId);
        }
    }

    /**
     * create  the search Assignproducts
     *
     * @return \Illuminate\Http\Response
     */
    public function createProduct($sellerId,$productId)
    {
        $baseProduct = $this->product->find($productId);

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view($this->_config['view'], compact('baseProduct', 'inventorySources'));
    }

    /**
     * Store a newly created resource in storage
     *
     * @return \Illuminate\Http\Response
     */
    public function store($sellerId, $productId)
    {
        $this->validate(request(), [
            'condition' => 'required',
            'description' => 'required'
        ]);

        $data = array_merge(request()->all(), [
                'product_id' => $productId,
                'seller_id' => $sellerId
            ]);

        $product = $this->sellerProduct->findOneWhere([
            'product_id' => $productId,
            'seller_id' => $sellerId,
            'condition' => $data['condition']
        ]);

        if ($product) {
            session()->flash('error', trans('seller::app.admin.seller.products.already-selling'));

            return redirect()->back();
        }

        $product = $this->sellerProduct->createAssign($data);

        session()->flash('success', 'seller::app.admin.seller.products.create-success');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->sellerProduct->find($id);

        if ($product->parent) {
            return redirect()->route('admin.seller.products.assign.edit', ['id' => $product->parent->id]);
        }

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view($this->_config['view'], compact('product', 'inventorySources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'description' => 'required'
        ]);

        $data = request()->all();

        $this->sellerProduct->updateAssign($data, $id);

        session()->flash('success', 'seller::app.admin.seller.products.update-success');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAssignProduct()
    {
        $data = request()->all();

        if (isset($data['seller_id'])) {
            $product = $this->sellerProduct->findOneWhere([
                'product_id' => $data['product_id'],
                'seller_id'  => $data['seller_id'],
                'condition'  => $data['condition']
            ]);

            if (! $product) {
                $this->sellerProduct->createAssign($data);

                session()->flash('success', trans('seller::app.admin.seller.products.success-assign'));
            } else {
                session()->flash('error', trans('seller::app.admin.seller.products.already-assign'));
            }
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->sellerProduct->findOrFail($id);

        try {
            $this->sellerProduct->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Product']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Product']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {
            $product = $this->sellerProduct->find($productId);

            if (isset($product)) {
                $this->sellerProduct->delete($productId);
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }
}
