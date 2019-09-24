@extends('seller::admin.layouts.content')

@section('page_title')
    {{ __('seller::app.admin.seller.products.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('seller::app.admin.seller.products.title') }}</h1>
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\Seller\DataGrids\Admin\ProductDataGrid')->render() !!}

        </div>
    </div>

@stop
