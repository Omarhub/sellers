
    <div class="tab" id="main-tab">
        <div class="tab-container">
            <div class="sticky-tabs-default sticky-bottom-tabs" id="main-link">
                <a class="tablinks" href="#main-tab" id="overview-active" onclick="openProductTab(event, 'Overview')">
                    {{ __('seller::app.shop.products.overview') }}
                </a>

                <a class="tablinks" href="#main-tab" onclick="openProductTab(event, 'Specification')">
                    {{ __('seller::app.shop.products.specification') }}
                </a>

                <a class="tablinks" href="#main-tab" onclick="openProductTab(event, 'Compare')">
                    {{ __('seller::app.shop.products.compare') }}
                </a>

                <a class="tablinks" href="#main-tab" id="seller-active" onclick="openProductTab(event, 'Seller')">
                    {{ __('seller::app.shop.products.seller') }}
                </a>

                <a class="tablinks" href="#main-tab" onclick="openProductTab(event, 'Review')">
                    {{ __('seller::app.shop.products.review') }}
                </a>
            </div>
        </div>
    </div>

    <div id="Overview" class="tabcontent">
        <div class="sale-section">
            <div class="section-content">
                <div class="product-main-content">

                    <div slot="body">
                        <div class="product-full-description">
                            {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

                                {!! $product->description !!}
                            {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}
                        </div>
                    </div>
                </div>

                {{-- <div class="product-image-details">
                    <div class="product-info">
                        <div class="product-image">
                            <a href="{{ route('shop.products.index', $product->url_key) }}" title="{{ $product->name }}">
                                <img class="desc-image" src="{{ $productBaseImage['medium_image_url'] }}"/>
                            </a>
                        </div>

                        <div class="product-name mt-20 product-name-overview">
                            <a href="{{ url()->to('/').'/products/'.$product->url_key }}" title="{{ $product->name }}">
                                <span>{{ $product->name }}</span>
                            </a>
                        </div>

                        <div class="product-price mt-10 product-price-overview">
                            @inject ('priceHelper', 'Webkul\Product\Helpers\Price')
                            @if ($product->type == 'configurable')
                                <span class="pro-price">{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>
                            @else
                                @if ($priceHelper->haveSpecialPrice($product))
                                    <span class="pro-price">{{ core()->currency($priceHelper->getSpecialPrice($product)) }}</span>
                                @else
                                    <span class="pro-price">{{ core()->currency($product->price) }}</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <div id="Specification" class="tabcontent">
        <div class="sale-section">
            <div class="section-content">
                <div class="product-main-content">
                    @include ('seller::shop.products.view.attributes')
                </div>
            </div>
        </div>
    </div>

    <div id="Compare" class="tabcontent">
        <div class="sale-section">
            <div class="section-content">
                <div class="product-main-content">
                    @include ('seller::shop.products.view.compare')
                </div>
            </div>
        </div>
    </div>

    <div id="Seller" class="tabcontent">
        <?php
        $baseProduct = $product->parent_id ? $product->parent : $product;

        $productRepository = app('Webkul\Seller\Repositories\ProductRepository');
        ?>

        <div class="sale-section">
            <div class="section-content" style="width:auto;">
                <div class="seller-product-list">
                    <div class="content" style="width: 1000px; padding: 15px;">
                        @if (count($productRepository->getSellerProducts($product)) > 0 )
                            <table id="productTabs">
                                <tr>
                                    <th>{{ __('seller::app.shop.products.condition') }}</th>
                                    <th>{{ __('seller::app.shop.products.price') }}</th>
                                    <th>{{ __('seller::app.shop.products.warranty') }}</th>
                                    <th>{{ __('seller::app.shop.products.seller-name') }}</th>
                                    <th>{{ __('seller::app.shop.products.quantity') }}</th>
                                    <th>{{ __('seller::app.shop.products.add-to-cart') }}</th>
                                </tr>
                            </table>
                        @else
                            <div class="no-seller">
                                {{ __('seller::app.shop.products.seller-not-found') }}
                            </div>
                        @endif

                        @foreach ($productRepository->getSellerProducts($product) as $sellerProduct)
                            <?php $sellerName = $productRepository->getSellerName($sellerProduct->seller->id)?>
                            {{--  --}}
                            <?php $attributes = [];
                            ?>

                            @if ($baseProduct->type == 'configurable')

                                <div class="options">
                                    <?php $options = [];

                                    $variantProduct = app('Webkul\Product\Repositories\ProductRepository')->findOrFail($sellerProduct->product_id);
                                    ?>

                                    @foreach ($baseProduct->product->super_attributes as $attribute)

                                        @foreach ($attribute->options as $option)

                                        @if ($variantProduct->{$attribute->code} == $option->id)

                                                <?php $attributes[$attribute->id] = $option->id; ?>

                                                <?php array_push($options, $attribute->name . ' : ' . $option->label); ?>

                                            @endif

                                        @endforeach

                                    @endforeach

                                </div>

                            @endif

                        {{--  --}}
                            <form action="{{ route('cart.add.seller.product', $baseProduct->id) }}" method="POST">
                                @csrf()
                                <input type="hidden" name="product" value="{{ $baseProduct->id }}">
                                <input type="hidden" name="seller_info[product_id]" value="{{ $sellerProduct->id }}">
                                <input type="hidden" name="seller_info[seller_id]" value="{{ $sellerProduct->seller->id }}">
                                <input type="hidden" name="seller_info[is_owner]" value="0">

                                @if ($baseProduct->type == 'configurable')
                                <input type="hidden" name="selected_configurable_option" value="{{ $sellerProduct->product_id }}">

                                    @foreach ($attributes as $attributeId => $optionId)
                                        <input type="hidden" name="super_attribute[{{$attributeId}}]" value="{{$optionId}}"/>
                                    @endforeach
                                @endif
                                <table id="productTabs" @if ($baseProduct->type == 'configurable') class="productTabs {{ $sellerProduct->product_id }}" @endif>
                                    <tr>
                                        <td>
                                            @if ($sellerProduct->condition == 'new')
                                                {{ __('seller::app.shop.products.new') }}
                                            @else
                                                {{ __('seller::app.shop.products.used') }}
                                            @endif
                                        </td>

                                        <td>
                                            {{ core()->currency($sellerProduct->price) }}
                                        </td>

                                        <td>
                                            @if ($sellerProduct->warranty)
                                                {{$sellerProduct->warranty . ' '. "Months"}}
                                            @else
                                                <span style="color:#0041ff">N/A</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{$sellerName->first_name . ' '. $sellerName->last_name}}
                                        </td>

                                        <td>

                                            <div class="control-group defined" style=" width: 132px; margin-bottom:0px !important">
                                                {{-- <input type="hidden" name="quantity" value="1" class="control"> --}}

                                                <div class="seller-quantity control-group" :class="[errors.has('quantity') ? 'has-error' : '']"
                                                sellerId="{{$sellerProduct->id}}" value="2">

                                                    <input class="control quantity-change minus" value="-" style="width: 35px; border-radius: 3px 0px 0px 3px;" id="tag<%{{$sellerProduct->id}}%>" readonly>

                                                    <input name="quantity" id="quantity_{{$sellerProduct->id}}" class="control quantity-change inputbox" value="1" v-validate="'required|numeric|min_value:1'" style="width: 60px; position: relative; margin-left: -4px; margin-right: -4px; border-right: none;border-left: none; border-radius: 0px;" data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;" readonly>

                                                    <input class="control quantity-change plus" id="tag<%{{$sellerProduct->id}}%>" value="+" style="width: 35px; padding: 0 12px; border-radius: 0px 3px 3px 0px;" readonly>

                                                    <span class="control-error" v-if="errors.has('quantity')">@{{ errors.first('quantity') }}</span>
                                                </div>

                                            </div>
                                        </td>

                                        <td>
                                            @if ($sellerProduct->haveSufficientQuantity(1))
                                                <div class="add-to-cart-btn seller-add-tocart">
                                                    <button type="submit" class="btn btn-black btn-lg" style="padding: 4px 8px;">
                                                        {{ __('seller::app.shop.products.add-to-cart') }}
                                                    </button>
                                                </div>
                                            @else
                                                <div class="stock-status">
                                                    {{ __('seller::app.shop.products.out-of-stock') }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </form>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="Review" class="tabcontent">
        <div class="sale-section">
            <div class="section-content">
                <div class="product-main-content">
                    @include ('seller::shop.products.view.reviews')
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>

    function openProductTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }


    </script>

<script>
        eventBus.$on('configurable-variant-selected-event', function(variantId) {

            var nonVisible = document.getElementsByClassName('productTabs');
            var visible = document.getElementsByClassName(variantId);

            for (let i=0; i < nonVisible.length; i++) {
                nonVisible[i].style.display = "none";
            }

            for (let i=0; i < visible.length; i++) {
                visible[i].style.display = "block";
            }
        });

        $(document).ready(function () {
            $('.plus').on('click', function() {
                var val = $(this).parent().find('.inputbox').attr('id');
                console.log(val);
                var quantity = document.getElementById(val).value;

                quantity = parseInt(quantity) + 1;

                document.getElementById(val).value = quantity;
                event.preventDefault();

            });

            $('.minus').on('click',function() {
                var val = $(this).parent().find('.inputbox').attr('id');

                var quantity = document.getElementById(val).value;
                console.log(quantity);
                if (quantity > 1) {
                    quantity = parseInt(quantity) - 1;
                } else {
                    alert('{{ __('shop::app.products.less-quantity') }}');
                }

                document.getElementById(val).value = quantity;
                event.preventDefault();

            });
        });

    </script>


@endpush