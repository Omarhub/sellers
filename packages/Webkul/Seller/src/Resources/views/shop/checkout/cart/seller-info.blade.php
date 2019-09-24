<?php

    $sellerRepository = app('Webkul\Seller\Repositories\SellerRepository');

    $productRepository = app('Webkul\Seller\Repositories\ProductRepository');

    if (isset($item->additional['seller_info']) && !$item->additional['seller_info']['is_owner']) {
        $seller = $sellerRepository->find($item->additional['seller_info']['seller_id']);
    } else {
        $seller = $productRepository->getSellerByProductId($item->product_id);
    }

?>

@if ($seller)
    <div class="seller-info" style="margin-bottom: 10px;">

        {!! __('seller::app.shop.products.sold-by')!!}

        <span style="color: #0041ff">{!! __($seller->first_name .' '.$seller->last_name)!!}</span>
    </div>
@endif