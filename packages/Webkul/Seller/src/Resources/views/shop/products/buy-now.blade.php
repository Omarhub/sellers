{!! view_render_event('bagisto.shop.products.buy_now.before', ['product' => $product]) !!}

{{-- <button type="submit" data-href="{{ route('shop.product.buynow', $product->product_id)}}" class="btn btn-lg btn-primary buynow buy-now-button" {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
    {{ __('shop::app.products.buy-now') }}
</button> --}}

<a href="#main-tab" id="scroll-down-buynow" class="btn btn-lg btn-primary buynow buy-now-button" {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? 'disabled' : '' }} >
        {{ __('shop::app.products.buy-now') }}
</a>

{!! view_render_event('bagisto.shop.products.buy_now.after', ['product' => $product]) !!}