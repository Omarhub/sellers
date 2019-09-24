@extends('seller::shop.layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.address.create.page-title') }}
@endsection

@section('content-wrapper')

    <div class="account-content">

        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">
            <div class="account-head mb-15">
                <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
                <span class="account-heading">{{ __('shop::app.customer.account.address.create.title') }}</span>
                <span></span>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.address.create.before') !!}

            <form method="post" action="{{ route('customer.address.create') }}" @submit.prevent="onSubmit">

                <div class="account-table-content">
                    @csrf

                    {!! view_render_event('bagisto.shop.customers.account.address.create_form_controls.before') !!}

                {{--<div class="control-group" :class="[errors.has('area') ? 'has-error' : '']">
                        <label for="area" class="required">{{ __('seller::app.shop.customer.account.address.area') }}</label>
                        <input type="text" class="control" name="area" v-validate="'required'" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.area') }}&quot;">
                        <span class="control-error" v-if="errors.has('area')">@{{ errors.first('area') }}</span>
                    </div> --}}

                    @include('seller::shop.area')

                    <div class="control-group" :class="[errors.has('block') ? 'has-error' : '']">
                        <label for="block" class="required">{{ __('seller::app.shop.customer.account.address.block') }}</label>
                        <input type="text" class="control" name="block" v-validate="'required|numeric'" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.block') }}&quot;">
                        <span class="control-error" v-if="errors.has('block')">@{{ errors.first('block') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('address1[]') ? 'has-error' : '']">
                        <label for="street" class="required">{{ __('seller::app.shop.customer.account.address.street') }}</label>
                        <input type="text" class="control" name="address1[]" v-validate="'required'" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.street') }}&quot;">
                        <span class="control-error" v-if="errors.has('address1[]')">@{{ errors.first('address1[]') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('avenue') ? 'has-error' : '']">
                        <label for="avenue">{{ __('seller::app.shop.customer.account.address.avenue') }}</label>
                        <input type="text" class="control" name="avenue" v-validate="'numeric'" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.avenue') }}&quot;">
                        <span class="control-error" v-if="errors.has('avenue')">@{{ errors.first('avenue') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('building') ? 'has-error' : '']">
                        <label for="building" class="required">{{ __('seller::app.shop.customer.account.address.building') }}</label>
                        <input type="text" class="control" name="building" v-validate="'required'" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.building') }}&quot;">
                        <span class="control-error" v-if="errors.has('building')">@{{ errors.first('building') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('floor') ? 'has-error' : '']">
                        <label for="floor">{{ __('seller::app.shop.customer.account.address.floor') }}</label>
                        <input type="text" class="control" name="floor" v-validate="'numeric'" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.floor') }}&quot;">
                        <span class="control-error" v-if="errors.has('floor')">@{{ errors.first('floor') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('flat') ? 'has-error' : '']">
                        <label for="flat">{{ __('seller::app.shop.customer.account.address.flat') }}</label>
                        <input type="text" class="control" name="flat" v-validate="'numeric'" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.flat') }}&quot;">
                        <span class="control-error" v-if="errors.has('flat')">@{{ errors.first('flat') }}</span>
                    </div>

                    {{-- <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                        <label for="city" class="required">{{ __('shop::app.customer.account.address.create.city') }}</label>
                        <input type="text" class="control" name="city" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.city') }}&quot;">
                        <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                    </div> --}}

                    <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="phone" class="required">{{ __('shop::app.customer.account.address.create.phone') }}</label>
                        <input type="text" class="control" name="phone" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.phone') }}&quot;">
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>

                    {!! view_render_event('bagisto.shop.customers.account.address.create_form_controls.after') !!}

                    <div class="button-group">
                        <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.account.address.create.submit') }}">
                        {{-- <button class="btn btn-primary btn-lg" type="submit">
                            {{ __('shop::app.customer.account.address.edit.submit') }}
                        </button> --}}
                    </div>

                </div>

            </form>

            {!! view_render_event('bagisto.shop.customers.account.address.create.after') !!}

        </div>
    </div>

@endsection