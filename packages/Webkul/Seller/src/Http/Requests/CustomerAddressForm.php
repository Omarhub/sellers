<?php

namespace Webkul\Seller\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerAddressForm extends FormRequest
{
    protected $rules;

    /**
     * Determine if the product is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (isset($this->get('billing')['address_id'])) {
            $this->rules = [
                'billing.address_id' => ['required'],
            ];
        } else {
            $this->rules = [
                'billing.first_name' => ['required'],
                'billing.last_name' => ['required'],
                'billing.email' => ['required'],
                'billing.address1' => ['required'],
                'billing.area' => ['required'],
                'billing.block' => ['required'],
                'billing.phone' => ['required'],
                'billing.building' => ['required']
            ];
        }

        if (isset($this->get('billing')['use_for_shipping']) && !$this->get('billing')['use_for_shipping']) {
            if (isset($this->get('shipping')['address_id'])) {
                $this->rules = array_merge($this->rules, [
                    'shipping.address_id' => ['required'],
                ]);
            } else {
                $this->rules = array_merge($this->rules, [
                    'shipping.first_name' => ['required'],
                    'shipping.last_name' => ['required'],
                    'shipping.email' => ['required'],
                    'shipping.address1' => ['required'],
                    'shipping.area' => ['required'],
                    'shipping.block' => ['required'],
                    'shipping.phone' => ['required'],
                    'shipping.building' => ['required']
                ]);
            }
        }

        return $this->rules;
    }
}