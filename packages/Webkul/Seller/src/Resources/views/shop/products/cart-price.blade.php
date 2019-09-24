{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

<div class="product-price">
    @inject ('priceHelper', 'Webkul\Product\Helpers\Price')

    @if ($product->type == 'configurable')
        <div class="buy-now-uper-section">
            <div class="price-as-low">
                <span class="price-label">
                    {{ __('shop::app.products.price-label') }}
                </span>
            </div>

            <div class="show-price">
                <span class="final-price" style="font-size:25px;">{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>
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
           <span style="font-weight: 300; font-size: 20px;">{{ __('seller::app.products.price') }}</span>

            <span style="font-size: 24px; margin-left: 6px;">{{ core()->currency($product->price) }}</span>
        </div>
        @endif
    @endif
</div>

{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}