@auth('customer')
    {!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    <a class="add-to-wishlist" href="{{ route('customer.wishlist.add', $product->product_id) }}" id="wishlist-changer">
        <span class="icon wishlist-icon" style="margin-left: 75px;"></span>
    </a>

    <span class="wishlist-text" style="margin-left: 46px; font-size: 12px;">
            {{ __('seller::app.products.add-wish-list') }}
    </span>

    {!! view_render_event('bagisto.shop.products.wishlist.after') !!}
@endauth