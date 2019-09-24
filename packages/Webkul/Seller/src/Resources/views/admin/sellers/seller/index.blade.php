@extends('seller::admin.layouts.content')

@section('page_title')
    {{ __('seller::app.admin.layouts.sellers') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('seller::app.admin.layouts.sellers') }}</h1>
            </div>

            <div class="page-action">
            <a href="{{ route('admin.seller.create') }}" class="btn btn-lg btn-primary">
                    {{ __('seller::app.admin.seller.add-seller-btn-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\Seller\DataGrids\Admin\SellerDataGrid')->render() !!}

        </div>
    </div>

@stop
