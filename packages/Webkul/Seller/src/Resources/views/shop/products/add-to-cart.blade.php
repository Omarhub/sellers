{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

{{-- <button type="submit" href="#main-tab" class="btn btn-lg btn-primary addtocart custom-button" {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? 'disabled' : '' }} >
    {{ __('shop::app.products.add-to-cart') }}
</button> --}}


<a href="#main-tab" id="scroll-down" class="btn btn-lg btn-primary addtocart custom-button" {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? 'disabled' : '' }} >
    {{ __('shop::app.products.add-to-cart') }}
</a>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}
