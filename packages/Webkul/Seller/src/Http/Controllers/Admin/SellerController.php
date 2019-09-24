<?php

namespace Webkul\Seller\Http\Controllers\Admin;

use Webkul\Seller\Repositories\SellerRepository as Seller;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Seller\Repositories\ProductRepository;

/**
 * Seller Controller
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SellerController extends Controller
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
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * ProductInventoryRepository object
     *
     * @var Object
     */
    protected $productInventoryRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Seller\Repositories\SellerRepository  $seller
     * @param  Webkul\Product\Repositories\ProductInventoryRepository $productInventoryRepository
     * @param  Webkul\Seller\Repositories\ProductRepository  $ProductRepository
     * @return void
     */
    public function __construct(
        Seller $seller,
        ProductInventoryRepository $productInventoryRepository,
        ProductRepository $productRepository
    )
    {
        $this->seller = $seller;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->productRepository = $productRepository;

        $this->_config = request('_config');
    }

    /**
     * display the specific seller
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * create specific seller
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store specific seller
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'required|unique:sellers,email',
        ]);

        $data = request()->all();

        $seller = $this->seller->create($data);

        session()->flash('success', trans('seller::app.admin.seller.save-message'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * edit specific seller
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seller = $this->seller->findOrFail($id);

        return view($this->_config['view'])->with('seller',$seller);
    }

    /**
     * update specific seller
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(),[
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'required',
        ]);

        $data = request()->all();

        $seller = $this->seller->update($data,$id);

        session()->flash('success',trans('seller::app.admin.seller.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * destroy specific seller
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->sellerProductDelete($id);

        $this->seller->delete($id);

        session()->flash('success', trans('seller::app.admin.seller.delete-success'));

        return response()->json(['message' => true], 200);
    }

    /**
     * mass destroy specific seller
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $sellerIds = explode(',', request()->input('indexes'));

        foreach ($sellerIds as $sellerId) {

            $this->sellerProductDelete($sellerId);

            $this->seller->delete($sellerId);
        }

        session()->flash('success', trans('seller::app.admin.seller.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * delete seller product from inventory
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sellerProductDelete($id) {

        $products = $this->productRepository->findWhere([
            'seller_id' => $id,
        ]);

        foreach ($products as $product) {
            $inventory = $this->productInventoryRepository->findOneWhere([
                'vendor_id' => $product->id
            ]);

            if ($inventory) {
                $this->productInventoryRepository->delete($inventory->id);
            }
        }
    }
}
