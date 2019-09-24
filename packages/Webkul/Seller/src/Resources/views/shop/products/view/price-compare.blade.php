{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

<div class="product-price">
    @inject ('priceHelper', 'Webkul\Product\Helpers\Price')

    @if ($product->type == 'configurable')
        <div class="buy-now-uper-section">
            <div class="show-price">
                <span class="final-price" style="font-size:18px;">{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>
            </div>
        </div>
    @else
        @if ($priceHelper->haveSpecialPrice($product))
        <div class="offer-control">
            <div class="sticker sale">
                {{ __('seller::app.products.offer') }}
            </div>

            <div class="special-price">
                <span class="special-price">{{ core()->currency($priceHelper->getSpecialPrice($product)) }}</span>
            </div>

            <div class="original-price">
                <span class="regular-price">{{ core()->currency($product->price) }}</span>
            </div>
        </div>
        @else
        <div class="simple-product-price">
            <span style="font-size: 18px; margin-left: 6px;">{{ core()->currency($product->price) }}</span>
        </div>
        @endif
    @endif
</div>

{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}