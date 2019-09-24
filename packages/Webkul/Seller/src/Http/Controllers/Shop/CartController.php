<?php

namespace Webkul\Seller\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Helpers\Price;
use Cart;

/**
 * Cart controller for the customer and guest users for adding and
 * removing the products in the cart.
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{

    /**
     * Protected Variables that holds instances of the repository classes.
     *
     * @param Array $_config
     * @param $cart
     * @param $cartItem
     * @param $customer
     * @param $product
     * @param $productView
     */
    protected $_config;

    protected $cart;

    protected $cartItem;

    protected $customer;

    protected $product;

    protected $suppressFlash = false;

    /**
     * Product price helper instance
    */
    protected $price;

    /**
     * WishlistRepository Repository object
     *
     * @var array
     */
    protected $wishlist;

    public function __construct(
        CartRepository $cart,
        CartItemRepository $cartItem,
        CustomerRepository $customer,
        ProductRepository $product,
        WishlistRepository $wishlist,
        Price $price
    )
    {

        $this->middleware('customer')->only(['moveToWishlist']);

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->product = $product;

        $this->wishlist = $wishlist;

        $this->price = $price;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the cart page which will be populated before the checkout process.
     *
     * @return Mixed
     */
    public function index()
    {
        return view($this->_config['view'])->with('cart', Cart::getCart());
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @return Mixed
     */
    public function add($id)
    {
        try {

            Event::fire('checkout.cart.add.before', $id);

            $result = $this->addToCart($id, request()->except('_token'));

            Event::fire('checkout.cart.add.after', $result);

            Cart::collectTotals();

            if ($result) {
                session()->flash('success', trans('shop::app.checkout.cart.item.success'));

                if (auth()->guard('customer')->user()) {
                    $customer = auth()->guard('customer')->user();

                    if (count($customer->wishlist_items)) {
                        foreach ($customer->wishlist_items as $wishlist) {
                            if ($wishlist->product_id == $id) {
                                $this->wishlist->delete($wishlist->id);
                            }
                        }
                    }
                }

                return redirect()->back();
            } else {
                session()->flash('warning', trans('shop::app.checkout.cart.item.error-add'));

                return redirect()->back();
            }

            return redirect()->route($this->_config['redirect']);

        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    public function addToCart($id, $data)
    {
        $cart = Cart::getCart();

        if ($cart != null) {
            $ifExists = $this->checkIfItemExists($id, $data);

            if ($ifExists) {
                $item = $this->cartItem->findOneByField('id', $ifExists);

                $data['quantity'] = $data['quantity'] + $item->quantity;

                $result = Cart::updateItem($id, $data, $ifExists);
            } else {
                $result = $this->createItem($id, $data);
            }

            return $result;
        } else {
            return Cart::create($id, $data);
        }
    }

    /**
     * To check if the items exists in the cart or not
     *
     * @return boolean
     */
    public function checkIfItemExists($id, $data) {
        $items = Cart::getCart()->items;

        foreach ($items as $item) {
            if ($id == $item->product_id && $item->seller_product_id == $data['seller_info']['product_id']) {
                $product = $this->product->findOnebyField('id', $id);

                if ($product->type == 'configurable') {
                    $variant = $this->product->findOneByField('id', $data['selected_configurable_option']);

                    if ($item->child->product_id == $data['selected_configurable_option']) {
                        return $item->id;
                    }
                } else {
                    return $item->id;
                }
            }
        }

        return 0;
    }

    /**
     * Create the item based on the type of product whether simple or configurable
     *
     * @return Mixed Array $item || Error
     */
    public function createItem($id, $data)
    {
        $childProduct = $configurable = false;

        $product = $this->product->findOneByField('id', $id);

        if ($product->type == 'configurable') {
            if (! isset($data['selected_configurable_option']) || ! $data['selected_configurable_option']) {
                return false;
            }

            $childProduct = $this->product->findOneByField('id', $data['selected_configurable_option']);

            $canAdd = $childProduct->haveSufficientQuantity($data['quantity']);

            if (! $canAdd) {
                session()->flash('warning', trans('shop::app.checkout.cart.quantity.inventory_warning'));

                return false;
            }
        } else {
            $canAdd = $product->haveSufficientQuantity($data['quantity']);

            if (! $canAdd) {
                session()->flash('warning', trans('shop::app.checkout.cart.quantity.inventory_warning'));

                return false;
            }
        }

        //Check if the product's information is proper or not
        if (! isset($data['product']) || ! isset($data['quantity'])) {
            session()->flash('error', trans('shop::app.checkout.cart.integrity.missing_fields'));

            return false;
        } else {
            if ($product->type == 'configurable' && ! isset($data['super_attribute'])) {
                session()->flash('error', trans('shop::app.checkout.cart.integrity.missing_options'));

                return false;
            }
        }

        $child = $childData = null;
        $additional = [];

        if ($product->type == 'configurable') {
            $price = $this->price->getMinimalPrice($childProduct);
        } else {
            $price = $this->price->getMinimalPrice($product);
        }

        $weight = ($product->type == 'configurable' ? $childProduct->weight : $product->weight);

        $parentData = [
            'sku' => $product->sku,
            'quantity' => $data['quantity'],
            'cart_id' => Cart::getCart()->id,
            'name' => $product->name,
            'price' => core()->convertPrice($price),
            'base_price' => $price,
            'total' => core()->convertPrice($price * $data['quantity']),
            'base_total' => $price * $data['quantity'],
            'weight' => $weight,
            'total_weight' => $weight * $data['quantity'],
            'base_total_weight' => $weight * $data['quantity'],
            'additional' => $additional,
            'type' => $product['type'],
            'product_id' => $product['id'],
            'additional' => $data,
            'seller_product_id' => $data['seller_info']['product_id'], //change
        ];

        if ($product->type == 'configurable') {
            $attributeDetails = Cart::getProductAttributeOptionDetails($childProduct);
            unset($attributeDetails['html']);

            $parentData['additional'] = array_merge($parentData['additional'], $attributeDetails);

            $childData['product_id'] = (int)$data['selected_configurable_option'];
            $childData['sku'] = $childProduct->sku;
            $childData['name'] = $childProduct->name;
            $childData['type'] = 'simple';
            $childData['cart_id'] = Cart::getCart()->id;
        }

        $item = $this->cartItem->create($parentData);

        if ($childData != null) {
            $childData['parent_id'] = $item->id;

            $this->cartItem->create($childData);
        }

        return $item;
    }
}