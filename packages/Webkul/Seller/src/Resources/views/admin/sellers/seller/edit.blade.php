@extends('seller::admin.layouts.content')

@section('page_title')
    {{ __('seller::app.admin.layouts.edit-title') }}
@stop

@section('content')

    <div class="content">
    <form method="POST" action="{{ route('sellers.admin.seller.update', $seller->id) }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('seller::app.admin.layouts.sellers') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('seller::app.admin.seller.save-seller-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf

                    <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                        <label for="first_name" class="required">{{ __('seller::app.admin.seller.firstname') }}</label>
                        <input type="text" class="control" name="first_name" v-validate="'required'" value="{{ $seller->first_name }}" data-vv-as="&quot;{{ __('seller::app.admin.seller.firstname') }}&quot;">
                        <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                        <label for="last_name" class="required">{{ __('seller::app.admin.seller.lastname') }}</label>
                        <input type="text" class="control" name="last_name" v-validate="'required'" value="{{ $seller->last_name }}" data-vv-as="&quot;{{ __('seller::app.admin.seller.lastname') }}&quot;">
                        <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email" class="required">{{ __('seller::app.admin.seller.email') }}</label>
                        <input type="email" class="control" name="email" v-validate="'required|email'" value="{{ $seller->email }}" data-vv-as="&quot;{{ __('seller::app.admin.seller.email') }}&quot;">
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop
