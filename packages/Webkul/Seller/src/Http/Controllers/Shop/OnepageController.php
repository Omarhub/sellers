<?php

namespace Webkul\Seller\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Checkout\Facades\Cart;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Payment\Facades\Payment;
use Webkul\Seller\Http\Requests\CustomerAddressForm;
use Webkul\Discount\Helpers\NonCouponAbleRule as NonCoupon;
use Webkul\Seller\Repositories\CartAddressRepository;
use Webkul\Seller\Repositories\CustomerAddressRepository;
use Webkul\Seller\Repositories\OrderRepository;
use Webkul\Discount\Helpers\ValidatesDiscount;

/**
 * Chekout controller controlller.
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OnepageController extends Controller
{
    /**
     * NoncouponAbleRule instance object
     *
     */
    protected $nonCoupon;

    /**
     * CartAddressRepository instance
     *
     * @var mixed
     */
    protected $cartAddress;

    /**
     * CustomerAddressRepository instance
     *
     * @var mixed
     */
    protected $customerAddress;

    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    /**
     * ValidatesDiscount instance object
     */
    protected $validatesDiscount;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Checkout\Repositories\CartAddressRepository  $cartAddress
     * @param  Webkul\Product\Repositories\CustomerAddressRepository  $customerAddress
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        NonCoupon $nonCoupon,
        CartAddressRepository $cartAddress,
        CustomerAddressRepository $customerAddress,
        ValidatesDiscount $validatesDiscount
    )
    {
        $this->orderRepository = $orderRepository;

        $this->nonCoupon = $nonCoupon;

        $this->cartAddress = $cartAddress;

        $this->customerAddress = $customerAddress;

        $this->validatesDiscount = $validatesDiscount;

        $this->_config = request('_config');
    }

    /**
     * Saves customer address.
     *
     * @param  \Webkul\Seller\Http\Requests\CustomerAddressForm $request
     *
     * @return \Illuminate\Http\Response
    */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = request()->all();

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (Cart::hasError() || ! $this->saveCustomerAddress($data) || ! $rates = Shipping::collectRates()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        $this->nonCoupon->apply();

        Cart::collectTotals();

        return response()->json($rates);
    }

    /**
     * Save customer address
     *
     * @return Mixed
     */
    public function saveCustomerAddress($data)
    {
        if (! $cart = Cart::getCart())
            return false;

        $billingAddress = $data['billing'];
        $shippingAddress = $data['shipping'];
        $billingAddress['cart_id'] = $shippingAddress['cart_id'] = $cart->id;

        if (isset($data['billing']['address_id']) && $data['billing']['address_id']) {
            $address = $this->customerAddress->findOneWhere(['id'=> $data['billing']['address_id']])->toArray();
            $billingAddress['first_name'] = $this->getCurrentCustomer()->user()->first_name;
            $billingAddress['last_name'] = $this->getCurrentCustomer()->user()->last_name;
            $billingAddress['email'] = $this->getCurrentCustomer()->user()->email;
            $billingAddress['address1'] = $address['address1'];
            $billingAddress['country'] = $address['country'];
            $billingAddress['state'] = $address['state'];
            $billingAddress['city'] = $address['city'];
            $billingAddress['postcode'] = $address['postcode'];
            $billingAddress['phone'] = $address['phone'];
            $billingAddress['area'] = $address['area'];
            $billingAddress['block'] = $address['block'];
            $billingAddress['avenue'] = $address['avenue'];
            $billingAddress['building'] = $address['building'];
            $billingAddress['street'] = $address['address1'][0];
            $billingAddress['flat'] = $address['flat'];
            $billingAddress['floor'] = $address['floor'];
        }

        if (isset($data['shipping']['address_id']) && $data['shipping']['address_id']) {
            $address = $this->customerAddress->findOneWhere(['id'=> $data['shipping']['address_id']])->toArray();
            $shippingAddress['first_name'] = $this->getCurrentCustomer()->user()->first_name;
            $shippingAddress['last_name'] = $this->getCurrentCustomer()->user()->last_name;
            $shippingAddress['email'] = $this->getCurrentCustomer()->user()->email;
            $shippingAddress['address1'] = $address['address1'];
            $shippingAddress['country'] = $address['country'];
            $shippingAddress['state'] = $address['state'];
            $shippingAddress['city'] = $address['city'];
            $shippingAddress['postcode'] = $address['postcode'];
            $shippingAddress['phone'] = $address['phone'];
            $shippingAddress['area'] = $address['area'];
            $shippingAddress['block'] = $address['block'];
            $shippingAddress['avenue'] = $address['avenue'];
            $shippingAddress['building'] = $address['building'];
            $shippingAddress['street'] = $address['address1'][0];
            $shippingAddress['flat'] = $address['flat'];
            $shippingAddress['floor'] = $address['floor'];
        }

        if (isset($data['billing']['save_as_address']) && $data['billing']['save_as_address']) {
            $billingAddress['customer_id']  = $this->getCurrentCustomer()->user()->id;
            $this->customerAddress->create($billingAddress);
        }

        if (isset($data['shipping']['save_as_address']) && $data['shipping']['save_as_address']) {
            $shippingAddress['customer_id']  = $this->getCurrentCustomer()->user()->id;
            $this->customerAddress->create($shippingAddress);
        }

        if ($billingAddressModel = $cart->billing_address) {
            $this->cartAddress->update($billingAddress, $billingAddressModel->id);

            if ($shippingAddressModel = $cart->shipping_address) {
                if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddress->update($billingAddress, $shippingAddressModel->id);
                } else {
                    $this->cartAddress->update($shippingAddress, $shippingAddressModel->id);
                }
            } else {
                if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'shipping']));
                } else {
                    $this->cartAddress->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
                }
            }
        } else {
            $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'billing']));

            if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'shipping']));
            } else {
                $this->cartAddress->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
            }
        }

        if ($this->getCurrentCustomer()->check()) {
            $cart->customer_email = $this->getCurrentCustomer()->user()->email;
            $cart->customer_first_name = $this->getCurrentCustomer()->user()->first_name;
            $cart->customer_last_name = $this->getCurrentCustomer()->user()->last_name;
        } else {
            $cart->customer_email = $cart->billing_address->email;
            $cart->customer_first_name = $cart->billing_address->first_name;
            $cart->customer_last_name = $cart->billing_address->last_name;
        }

        $cart->save();

        return true;
    }

    /**
     * Return current logged in customer
     *
     * @return Customer | Boolean
     */
    public function getCurrentCustomer()
    {
        $guard = request()->has('token') ? 'api' : 'customer';

        return auth()->guard($guard);
    }

    /**
     * Saves order.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveOrder()
    {
        if (Cart::hasError())
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);

        Cart::collectTotals();

        $this->validateOrder();

        $cart = Cart::getCart();

        if ($redirectUrl = Payment::getRedirectUrl($cart)) {
            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl
            ]);
        }

        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        Cart::deActivateCart();

        session()->flash('order', $order);

        return response()->json([
            'success' => true,
        ]);
    }

     /**
     * Validate order before creation
     *
     * @return mixed
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        $this->validatesDiscount->validate($cart);

        if (! $cart->shipping_address) {
            throw new \Exception(trans('Please check shipping address.'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('Please check billing address.'));
        }

        if (! $cart->selected_shipping_rate) {
            throw new \Exception(trans('Please specify shipping method.'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('Please specify payment method.'));
        }
    }
}