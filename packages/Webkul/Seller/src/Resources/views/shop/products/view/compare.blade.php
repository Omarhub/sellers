@inject ('productViewHelper', 'Webkul\Product\Helpers\View')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

{!! view_render_event('seller.shop.products.view.compare.before', ['product' => $product]) !!}

@if ($customAttributeValues = $productViewHelper->getAdditionalData($product))

    <?php
        $productRepository = app('Webkul\Product\Repositories\ProductFlatRepository');
        $productAttribute = app('Webkul\Product\Repositories\ProductRepository');
        $attributes = app('Webkul\Seller\Repositories\ProductRepository');
        $attribute = $attributes->getFamilyAttribute($product);
    ?>

    <div slot="body product-compare">
        <div class="main-container-compare">
            <div class="product-name-container">
                <div class="product-name">
                    <div class="products-attribute-name" style="margin-top:80px;">
                        <span>{{ __('seller::app.shop.products.products') }}</span>
                    </div>
                </div>

                @foreach ($customAttributeValues as $attributeName)
                    @if ($attributeName['label'])
                        <div class="product-attribute">
                            <span>{{ $attributeName['label'] }}</span>
                        </div>
                    @else
                        <div class="product-attribute">
                            <span>{{ $attributeName['admin_name'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            @foreach ($attribute as $compareAttribute)

                <?php
                    $compareProductBaseImage = $productImageHelper->getProductBaseImage($compareAttribute);
                    $customAttribute = $productViewHelper->getAdditionalData($compareAttribute);
                ?>
                <div class="product-detail-container">
                    <div class="product-image-container">
                        <div class="products-name-attribute">
                            <span class="product-compare-name heading">{{$compareAttribute->name}}</span>
                        </div>

                        <div class="product-attribute-image">
                            <img class="desc-image" src="{{ $compareProductBaseImage['medium_image_url'] }}" style="height:100px; width:100px;"/>
                        </div>

                        <div class="products-name-attribute">
                            <span class="product-compare-name heading">
                                @include ('seller::shop.products.view.price-compare', ['product' => $compareAttribute])
                            </span>
                        </div>

                        <div class="product-attribute-value">
                            <form action="{{ route('cart.add.seller.product', $compareAttribute->id) }}" method="POST">
                                @csrf()
                                <input type="hidden" name="product" value="{{ $compareAttribute->id }}">
                                <input type="hidden" name="quantity" value="1" class="control">

                                @if ($compareAttribute->haveSufficientQuantity(1))
                                    <div class="add-to-cart-btn seller-add-tocart">
                                        <button type="submit" class="btn btn-lg btn-primary addtocart custom-button" style="padding: 8px 12px;">
                                            {{ __('seller::app.shop.products.add-to-cart') }}
                                        </button>
                                    </div>
                                @else
                                    <div class="not-in-stock" style="margin-top: -8px;">
                                        {{ __('seller::app.shop.products.out-of-stock') }}
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    @foreach ($customAttribute as $productAttributes)
                        <div class="product-attribute-container">
                            <div class="product-attribute">
                                @if($productAttributes['value'])
                                    <span class="product-compare-name">{{$productAttributes['value']}}</span>
                                @else
                                    <span class="product-compare-name" style="font-size: 15px;">N/A</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}