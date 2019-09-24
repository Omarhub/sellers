<?php

namespace Webkul\Seller\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Seller\Repositories\CustomerAddressRepository;
use Auth;

/**
 * Customer Address controlller.
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * CustomerRepository object
     *
     * @var array
     */
    protected $customer;

    /**
     * CustomerAddressRepository object
     *
     * @var array
     */
    protected $address;

    public function __construct(
        CustomerRepository $customer,
        CustomerAddressRepository $address
    )
    {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->customer = auth()->guard('customer')->user();

        $this->address = $address;
    }

    /**
     * Create a new address for customer.
     *
     * @return view
     */
    public function store()
    {
        request()->merge(['address1' => implode(PHP_EOL, array_filter(request()->input('address1')))]);

        $data = collect(request()->input())->except('_token')->toArray();

        $this->validate(request(), [
            'address1' => 'string|required',
            'phone' => 'required',
            'area' => 'string|required',
            'block' => 'required',
            'building' => 'string|required'
        ]);

        $cust_id['customer_id'] = $this->customer->id;
        $data = array_merge($cust_id, $data);

        if ($this->customer->addresses->count() == 0) {
            $data['default_address'] = 1;
        }

        if ($this->address->create($data)) {
            session()->flash('success', trans('shop::app.customer.account.address.create.success'));

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', trans('shop::app.customer.account.address.create.error'));

            return redirect()->back();
        }
    }

    /**
     * Edit's the premade resource of customer called
     * Address.
     *
     * @return redirect
     */
    public function update($id)
    {
        request()->merge(['address1' => implode(PHP_EOL, array_filter(request()->input('address1')))]);

        $this->validate(request(), [
            'address1' => 'string|required',
            'phone' => 'required',
            'area' => 'string|required',
            'block' => 'required',
            'building' => 'string|required'
        ]);

        $data = collect(request()->input())->except('_token')->toArray();

        $addresses = $this->customer->addresses;

        foreach($addresses as $address) {
            if ($id == $address->id) {
                session()->flash('success', trans('shop::app.customer.account.address.edit.success'));

                $this->address->update($data, $id);

                return redirect()->route('customer.address.index');
            }
        }

        session()->flash('warning', trans('shop::app.security-warning'));

        return redirect()->route('customer.address.index');
    }
}