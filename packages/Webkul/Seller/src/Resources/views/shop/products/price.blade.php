{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

<div class="product-price">

    @inject ('priceHelper', 'Webkul\Product\Helpers\Price')
    @inject ('priceHelperSeller', 'Webkul\Seller\Repositories\ProductRepository')

    @if ($product->type == 'configurable')
        <span class="price-label">{{ __('shop::app.products.price-label') }}</span>

        <span>{{ core()->currency($priceHelperSeller->getSellerMinimalPrice($product)) }}</span>
    @else
        <span class="price-label">{{ __('shop::app.products.price-label') }}</span>
        <span>{{ core()->currency($priceHelperSeller->getSellerMinimalPrice($product)) }}</span>

    @endif
</div>

{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}