<form data-vv-scope="address-form">

    <div class="form-container" v-if="!this.new_billing_address">
        <div class="form-header mb-30">
            <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.billing-address') }}</span>

            <a class="btn btn-lg btn-primary" @click = newBillingAddress()>
                {{ __('shop::app.checkout.onepage.new-address') }}
            </a>
        </div>
        <div class="address-holder">
            <div class="address-card" v-for='(addresses, index) in this.allAddress'>
                <div class="checkout-address-content" style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <label class="radio-container" style="float: right; width: 10%;">
                        <input type="radio" v-validate="'required'" id="billing[address_id]" name="billing[address_id]" :value="addresses.id" v-model="address.billing.address_id" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.billing-address') }}&quot;">
                        <span class="checkmark"></span>
                    </label>

                    <ul class="address-card-list" style="float: right; width: 85%;">
                        <li class="mb-10">
                            <b>@{{ allAddress.first_name }} @{{ allAddress.last_name }},</b>
                        </li>

                        <li class="mt-5">
                            @{{ addresses.area }}, @{{ addresses.block }}
                        </li>

                        <li class="mt-5">
                            @{{ addresses.address1 }},
                        </li>

                        <li class="mt-5">
                            @{{ addresses.avenue }}, @{{ addresses.building }}
                        </li>

                        {{-- <li class="mb-5">
                            @{{ addresses.city }},
                        </li> --}}

                        <li>
                            <b>{{ __('shop::app.customer.account.address.index.contact') }}</b> : @{{ addresses.phone }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="control-group" :class="[errors.has('address-form.billing[address_id]') ? 'has-error' : '']">
                <span class="control-error" v-if="errors.has('address-form.billing[address_id]')">
                    @{{ errors.first('address-form.billing[address_id]') }}
                </span>
            </div>
        </div>
        <div class="control-group mt-5">
            <span class="checkbox">
                <input type="checkbox" id="billing[use_for_shipping]" name="billing[use_for_shipping]" v-model="address.billing.use_for_shipping"/>
                    <label class="checkbox-view" for="billing[use_for_shipping]"></label>
                    {{ __('shop::app.checkout.onepage.use_for_shipping') }}
            </span>

        </div>
    </div>

    <div class="form-container" v-if="this.new_billing_address">

        <div class="form-header">
            <h1>{{ __('shop::app.checkout.onepage.billing-address') }}</h1>

            @guest('customer')
                <a class="btn btn-lg btn-primary" href="{{ route('customer.session.index') }}">
                    {{ __('shop::app.checkout.onepage.sign-in') }}
                </a>
            @endguest

            @auth('customer')
                <a class="btn btn-lg btn-primary" @click = backToSavedBillingAddress()>
                    {{ __('shop::app.checkout.onepage.back') }}
                </a>
            @endauth
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[first_name]') ? 'has-error' : '']">
            <label for="billing[first_name]" class="required">
                {{ __('shop::app.checkout.onepage.first-name') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[first_name]" name="billing[first_name]" v-model="address.billing.first_name" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[first_name]')">
                @{{ errors.first('address-form.billing[first_name]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[last_name]') ? 'has-error' : '']">
            <label for="billing[last_name]" class="required">
                {{ __('shop::app.checkout.onepage.last-name') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[last_name]" name="billing[last_name]" v-model="address.billing.last_name" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[last_name]')">
                @{{ errors.first('address-form.billing[last_name]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[email]') ? 'has-error' : '']">
            <label for="billing[email]" class="required">
                {{ __('shop::app.checkout.onepage.email') }}
            </label>

            <input type="text" v-validate="'required|email'" class="control" id="billing[email]" name="billing[email]" v-model="address.billing.email" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[email]')">
                @{{ errors.first('address-form.billing[email]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[area]') ? 'has-error' : '']">
            <label for="billing[area]" class="required">
                {{ __('seller::app.shop.customer.account.address.area') }}
            </label>

            <input type="text" class="control" autocomplete="off" v-model="term" placeholder="{{ __('seller::app.shop.customer.account.address.search-hint') }}" v-on:keyup="search()" >

            <input type="hidden" v-validate="'required'" class="control" id="billing[area]" name="billing[area]" v-model="address.billing.area" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.area') }}&quot;"/>

            <span class="filter-tag" style="text-transform: capitalize; margin-right: 0px; justify-content: flex-start" v-if="address.billing.area">
                <span class="wrapper" style="margin-left: 0px; margin-right: 10px;">
                    @{{ address.billing.area }}
                <span class="icon cross-icon" @click="removeArea('billing')"></span>
                </span>
            </span>

            <div class="linked-product-search-result">
                <ul>
                    <li v-for='(area, index) in searchArea' v-if='searchArea.length' @click="addArea('billing', area.name)" style="color: blue;">
                        @{{ area.name }}
                    </li>

                    <li v-if="term.length > 2 && no_result">
                        {{ __('seller::app.shop.customer.account.address.no-result-found') }}
                    </li>
                </ul>
            </div>

            <span class="control-error" v-if="errors.has('address-form.billing[area]')">
                @{{ errors.first('address-form.billing[area]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[block]') ? 'has-error' : '']">
            <label for="billing[block]" class="required">
                {{ __('seller::app.shop.customer.account.address.block') }}
            </label>

            <input type="text" v-validate="'required|numeric'" class="control" id="billing[block]" name="billing[block]" v-model="address.billing.block" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.block') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[block]')">
                @{{ errors.first('address-form.billing[block]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[address1][]') ? 'has-error' : '']">
            <label for="billing_address_0" class="required">
                {{ __('shop::app.checkout.onepage.address1') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing_address_0" name="billing[address1][]" v-model="address.billing.address1[0]" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[address1][]')">
                @{{ errors.first('address-form.billing[address1][]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[avenue]') ? 'has-error' : '']">
            <label for="billing[avenue]">
                {{ __('seller::app.shop.customer.account.address.avenue') }}
            </label>

            <input type="text" v-validate="'numeric'" class="control" id="billing[avenue]" name="billing[avenue]" v-model="address.billing.avenue" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.avenue') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[avenue]')">
                @{{ errors.first('address-form.billing[avenue]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[building]') ? 'has-error' : '']">
            <label for="billing[building]" class="required">
                {{ __('seller::app.shop.customer.account.address.building') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[building]" name="billing[building]" v-model="address.billing.building" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.building') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[building]')">
                @{{ errors.first('address-form.billing[building]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[flat]') ? 'has-error' : '']">
            <label for="billing[flat]">
                {{ __('seller::app.shop.customer.account.address.flat') }}
            </label>

            <input type="text" v-validate="'numeric'" class="control" id="billing[flat]" name="billing[flat]" v-model="address.billing.flat" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.flat') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[flat]')">
                @{{ errors.first('address-form.billing[flat]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[floor]') ? 'has-error' : '']">
            <label for="billing[floor]">
                {{ __('seller::app.shop.customer.account.address.floor') }}
            </label>

            <input type="text" v-validate="'numeric'" class="control" id="billing[floor]" name="billing[floor]" v-model="address.billing.floor" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.floor') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[floor]')">
                @{{ errors.first('address-form.billing[floor]') }}
            </span>
        </div>

        {{-- <div class="control-group" :class="[errors.has('address-form.billing[city]') ? 'has-error' : '']">
            <label for="billing[city]" class="required">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[city]" name="billing[city]" v-model="address.billing.city" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[city]')">
                @{{ errors.first('address-form.billing[city]') }}
            </span>
        </div> --}}

        <div class="control-group" :class="[errors.has('address-form.billing[phone]') ? 'has-error' : '']">
            <label for="billing[phone]" class="required">
                {{ __('shop::app.checkout.onepage.phone') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="billing[phone]" name="billing[phone]" v-model="address.billing.phone" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[phone]')">
                @{{ errors.first('address-form.billing[phone]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.billing[direction]') ? 'has-error' : '']">
            <label for="billing[direction]">
                {{ __('seller::app.shop.customer.account.address.direction') }}
            </label>

            <input type="text" class="control" id="billing[direction]" name="billing[direction]" v-model="address.billing.direction" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.direction') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.billing[direction]')">
                @{{ errors.first('address-form.billing[direction]') }}
            </span>
        </div>

        <div class="control-group">
            <span class="checkbox">
                <input type="checkbox" id="billing[use_for_shipping]" name="billing[use_for_shipping]" v-model="address.billing.use_for_shipping"/>
                <label class="checkbox-view" for="billing[use_for_shipping]"></label>
                {{ __('shop::app.checkout.onepage.use_for_shipping') }}
            </span>

        </div>

        @auth('customer')
            <div class="control-group">
                <span class="checkbox">
                    <input type="checkbox" id="billing[save_as_address]" name="billing[save_as_address]" v-model="address.billing.save_as_address"/>
                    <label class="checkbox-view" for="billing[save_as_address]"></label>
                    {{ __('shop::app.checkout.onepage.save_as_address') }}
                </span>
            </div>
        @endauth

    </div>

    <div class="form-container" v-if="!address.billing.use_for_shipping && !this.new_shipping_address">
        <div class="form-header mb-30">
            <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.shipping-address') }}</span>

            <a class="btn btn-lg btn-primary" @click=newShippingAddress()>
                {{ __('shop::app.checkout.onepage.new-address') }}
            </a>
        </div>

        <div class="address-holder">
            <div class="address-card" v-for='(addresses, index) in this.allAddress'>
                <div class="checkout-address-content" style="display: flex; flex-direction: row; justify-content: space-between; width: 100%;">
                    <label class="radio-container" style="float: right; width: 10%;">
                        <input v-validate="'required'" type="radio" id="shipping[address_id]" name="shipping[address_id]" v-model="address.shipping.address_id" :value="addresses.id"
                        data-vv-as="&quot;{{ __('shop::app.checkout.onepage.shipping-address') }}&quot;">
                        <span class="checkmark"></span>
                    </label>

                    <ul class="address-card-list" style="float: right; width: 85%;">
                        <li class="mb-10">
                            <b>@{{ allAddress.first_name }} @{{ allAddress.last_name }},</b>
                        </li>

                        <li class="mt-5">
                            @{{ addresses.area }}, @{{ addresses.block }}
                        </li>

                        <li class="mt-5">
                            @{{ addresses.address1 }},
                        </li>

                        <li class="mt-5">
                            @{{ addresses.avenue }}, @{{ addresses.building }}
                        </li>

                        {{-- <li class="mb-5">
                            @{{ addresses.city }},
                        </li> --}}

                        <li>
                            <b>{{ __('shop::app.customer.account.address.index.contact') }}</b> : @{{ addresses.phone }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="control-group" :class="[errors.has('address-form.shipping[address_id]') ? 'has-error' : '']">
                <span class="control-error" v-if="errors.has('address-form.shipping[address_id]')">
                    @{{ errors.first('address-form.shipping[address_id]') }}
                </span>
            </div>

        </div>
    </div>

    <div class="form-container" v-if="!address.billing.use_for_shipping && this.new_shipping_address">

        <div class="form-header">
            <h1>{{ __('shop::app.checkout.onepage.shipping-address') }}</h1>

            @auth('customer')
                <a class="btn btn-lg btn-primary" @click = backToSavedShippingAddress()>
                    {{ __('shop::app.checkout.onepage.back') }}
                </a>
            @endauth
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[first_name]') ? 'has-error' : '']">
            <label for="shipping[first_name]" class="required">
                {{ __('shop::app.checkout.onepage.first-name') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[first_name]" name="shipping[first_name]" v-model="address.shipping.first_name" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.first-name') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[first_name]')">
                @{{ errors.first('address-form.shipping[first_name]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[last_name]') ? 'has-error' : '']">
            <label for="shipping[last_name]" class="required">
                {{ __('shop::app.checkout.onepage.last-name') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[last_name]" name="shipping[last_name]" v-model="address.shipping.last_name" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.last-name') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[last_name]')">
                @{{ errors.first('address-form.shipping[last_name]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[email]') ? 'has-error' : '']">
            <label for="shipping[email]" class="required">
                {{ __('shop::app.checkout.onepage.email') }}
            </label>

            <input type="text" v-validate="'required|email'" class="control" id="shipping[email]" name="shipping[email]" v-model="address.shipping.email" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.email') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[email]')">
                @{{ errors.first('address-form.shipping[email]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[area]') ? 'has-error' : '']">
            <label for="shipping[area]" class="required">
                {{ __('seller::app.shop.customer.account.address.area') }}
            </label>

            <input type="text" class="control" autocomplete="off" v-model="term" placeholder="{{ __('seller::app.shop.customer.account.address.search-hint') }}" v-on:keyup="search()" >

            <input type="hidden" v-validate="'required'" class="control" id="shipping[area]" name="shipping[area]" v-model="address.shipping.area" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.area') }}&quot;"/>

            <span class="filter-tag" style="text-transform: capitalize; margin-right: 0px; justify-content: flex-start" v-if="address.shipping.area">
                <span class="wrapper" style="margin-left: 0px; margin-right: 10px;">
                    @{{ address.shipping.area }}
                <span class="icon cross-icon" @click="removeArea('shipping')"></span>
                </span>
            </span>

            <div class="linked-product-search-result">
                <ul>
                    <li v-for='(area, index) in searchArea' v-if='searchArea.length' @click="addArea('shipping', area.name)" style="color: blue;">
                        @{{ area.name }}
                    </li>

                    <li v-if="term.length > 2 && no_result">
                        {{ __('seller::app.shop.customer.account.address.no-result-found') }}
                    </li>
                </ul>
            </div>

            <span class="control-error" v-if="errors.has('address-form.shipping[area]')">
                @{{ errors.first('address-form.shipping[area]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[block]') ? 'has-error' : '']">
            <label for="shipping[block]" class="required">
                {{ __('seller::app.shop.customer.account.address.block') }}
            </label>

            <input type="text" v-validate="'required|numeric'" class="control" id="shipping[block]" name="shipping[block]" v-model="address.shipping.block" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.block') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[block]')">
                @{{ errors.first('address-form.shipping[block]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[address1][]') ? 'has-error' : '']">
            <label for="shipping_address_0" class="required">
                {{ __('shop::app.checkout.onepage.address1') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping_address_0" name="shipping[address1][]" v-model="address.shipping.address1[0]" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.address1') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[address1][]')">
                @{{ errors.first('address-form.shipping[address1][]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[avenue]') ? 'has-error' : '']">
            <label for="shipping[avenue]">
                {{ __('seller::app.shop.customer.account.address.avenue') }}
            </label>

            <input type="text" v-validate="'numeric'" class="control" id="shipping[avenue]" name="shipping[avenue]" v-model="address.shipping.avenue" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.avenue') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[avenue]')">
                @{{ errors.first('address-form.shipping[avenue]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[building]') ? 'has-error' : '']">
            <label for="shipping[building]" class="required">
                {{ __('seller::app.shop.customer.account.address.building') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[building]" name="shipping[building]" v-model="address.shipping.building" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.building') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[building]')">
                @{{ errors.first('address-form.shipping[building]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[flat]') ? 'has-error' : '']">
            <label for="shipping[flat]">
                {{ __('seller::app.shop.customer.account.address.flat') }}
            </label>

            <input type="text" v-validate="'numeric'" class="control" id="shipping[flat]" name="shipping[flat]" v-model="address.shipping.flat" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.flat') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[flat]')">
                @{{ errors.first('address-form.shipping[flat]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[floor]') ? 'has-error' : '']">
            <label for="shipping[floor]">
                {{ __('seller::app.shop.customer.account.address.floor') }}
            </label>

            <input type="text" v-validate="'numeric'" class="control" id="shipping[floor]" name="shipping[floor]" v-model="address.shipping.floor" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.floor') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[floor]')">
                @{{ errors.first('address-form.shipping[floor]') }}
            </span>
        </div>

        {{-- <div class="control-group" :class="[errors.has('address-form.shipping[city]') ? 'has-error' : '']">
            <label for="shipping[city]" class="required">
                {{ __('shop::app.checkout.onepage.city') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[city]" name="shipping[city]" v-model="address.shipping.city" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.city') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[city]')">
                @{{ errors.first('address-form.shipping[city]') }}
            </span>
        </div> --}}

        <div class="control-group" :class="[errors.has('address-form.shipping[phone]') ? 'has-error' : '']">
            <label for="shipping[phone]" class="required">
                {{ __('shop::app.checkout.onepage.phone') }}
            </label>

            <input type="text" v-validate="'required'" class="control" id="shipping[phone]" name="shipping[phone]" v-model="address.shipping.phone" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.phone') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[phone]')">
                @{{ errors.first('address-form.shipping[phone]') }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.shipping[direction]') ? 'has-error' : '']">
            <label for="shipping[direction]">
                {{ __('seller::app.shop.customer.account.address.floor.direction') }}
            </label>

            <input type="text" class="control" id="shipping[direction]" name="shipping[direction]" v-model="address.shipping.direction" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.direction') }}&quot;"/>

            <span class="control-error" v-if="errors.has('address-form.shipping[direction]')">
                @{{ errors.first('address-form.shipping[direction]') }}
            </span>
        </div>

        @auth('customer')
            <div class="control-group">
                <span class="checkbox">
                    <input type="checkbox" id="shipping[save_as_address]" name="shipping[save_as_address]" v-model="address.shipping.save_as_address"/>
                    <label class="checkbox-view" for="shipping[save_as_address]"></label>
                    {{ __('shop::app.checkout.onepage.save_as_address') }}
                </span>
            </div>
        @endauth

    </div>

</form>