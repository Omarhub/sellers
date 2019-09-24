@extends('seller::shop.layouts.master')

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : str_limit(strip_tags($product->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $product->meta_keywords }}"/>
@stop

@section('content-wrapper')
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <section class="product-detail">
        <div class="layouter">
            <product-view>
                <div class="form-container">
                    @csrf()

                    <input type="hidden" name="product" value="{{ $product->product_id }}">

                    @include ('seller::shop.products.view.gallery')

                    <div class="details">
                        <div class="product-heading">
                            <span class="heading-span">
                                <h2>{{ $product->name }}</h2>
                            </span>
                        </div>

                        @include ('shop::products.review', ['product' => $product])

                        {!! view_render_event('bagisto.shop.products.view.short_description.before', ['product' => $product]) !!}

                        <div class="description">
                            {!! $product->short_description !!}
                        </div>

                        {!! view_render_event('bagisto.shop.products.view.short_description.after', ['product' => $product]) !!}


                        {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                        {{-- <div class="quantity control-group" :class="[errors.has('quantity') ? 'has-error' : '']">

                            <label class="required">{{ __('shop::app.products.quantity') }}</label>

                            <input class="control quantity-change" value="-" style="width: 35px; border-radius: 3px 0px 0px 3px;" onclick="updateQunatity('remove')" readonly>

                            <input name="quantity" id="quantity" class="control quantity-change" value="1" v-validate="'required|numeric|min_value:1'" style="width: 60px; position: relative; margin-left: -4px; margin-right: -4px; border-right: none;border-left: none; border-radius: 0px;" data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;" readonly>

                            <input class="control quantity-change" value="+" style="width: 35px; padding: 0 12px; border-radius: 0px 3px 3px 0px;" onclick=updateQunatity('add') readonly>

                            <span class="control-error" v-if="errors.has('quantity')">@{{ errors.first('quantity') }}</span>
                        </div> --}}

                        {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                        @if ($product->type == 'configurable')
                            <input type="hidden" value="true" name="is_configurable">
                        @else
                            <input type="hidden" value="false" name="is_configurable">
                        @endif

                        <div class="quantity">
                            @include ('shop::products.view.configurable-options')</div>
                        </div>

                    <div class="price-control">
                        <div class="product-prices">

                            @include ('seller::shop.products.cart-price', ['product' => $product])

                            <div class="stock-item">
                                @include ('shop::products.view.stock', ['product' => $product])
                            </div>
                        </div>

                        <div class="add-wishlist">
                            @include ('seller::shop.products.wishlist')
                        </div>

                        <div class="buy-now-control">
                            {{-- @include ('seller::shop.products.add-to-cart', ['product' => $product]) --}}

                            @include ('seller::shop.products.buy-now')
                        </div>
                    </div>
                </div>
            </product-view>

            @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
            @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
            @inject ('priceHelper', 'Webkul\Product\Helpers\Price')
            <?php $productBaseImage = $productImageHelper->getProductBaseImage($product); ?>

            @include ('seller::shop.products.product-tab')

            @include ('shop::products.view.related-products')

            @include ('shop::products.view.up-sells')
        </div>
    </section>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
@endsection

@push('scripts')

    <script type="text/x-template" id="product-view-template">
        <form method="POST" id="product-form" action="{{ route('cart.add', $product->product_id) }}" @click="onSubmit($event)">

            <slot></slot>

        </form>
    </script>

    <script>
        //added
        $(document).ready(function(){
            $(this).scrollTop(0);

            var addTOButton = document.getElementsByClassName('add-to-buttons')[0];
            document.getElementById('loader').style.display="none";
            addTOButton.style.display="flex";

            $('#scroll-down-buynow').click(function(){
                openProductTab(event, 'Seller');
                var default_active = document.getElementById("seller-active");

                default_active.className += " active";
            });

            var sbHeight = window.innerHeight * (window.innerHeight / document.body.offsetHeight);
            var y = $('#main-link').position();
            var mainLink = y.top;
            var x = $("#main-tab").position();
            var mainTab = x.top;
            var windowbottom = $(window).height();

            window.addEventListener('scroll', function() {
                var tops = $("#main-link").scrollTop()

                var links = $('#main-tab').position();
                var links = tops.top;

                var lastScrollTop = 0;
                st = $(this).scrollTop();

                if (st < lastScrollTop) {
                    if ($(this).scrollTop() <= mainTab) {

                        var list = document.getElementById('main-link');

                        $('#main-link').removeAttr('class');
                        list.classList.add("sticky-tabs-default");
                        list.classList.add("sticky-bottom-tabs");
                    }
                }
                else {
                    var height = $(this).scrollTop()+sbHeight;
                    if (height >= mainLink || $(this).scrollTop() >= windowbottom) {

                        var list = document.getElementById('main-link');

                        $('#main-link').removeAttr('class');
                        list.classList.add("sticky-tabs-default");
                        list.classList.add("non-sticky-tabs");

                        if ($(this).scrollTop() >= 795) {

                            $('#main-link').removeAttr('class');
                            list.classList.add("sticky-tabs-default");
                            list.classList.add("sticky-top-tabs");
                        }
                    } else if(height <= mainLink || $(this).scrollTop() <= windowbottom) {

                        var list = document.getElementById('main-link');
                        $('#main-link').removeAttr('class');
                        list.classList.add("sticky-tabs-default");
                        list.classList.add("sticky-bottom-tabs");
                    }
                }
                lastScrollTop = st;
            });
        });
        // added
        Vue.component('product-view', {

            template: '#product-view-template',

            inject: ['$validator'],

            methods: {
                onSubmit: function(e) {
                    if (e.target.getAttribute('type') != 'submit')
                        return;

                    e.preventDefault();

                    this.$validator.validateAll().then(function (result) {
                        if (result) {
                          if (e.target.getAttribute('data-href')) {
                            window.location.href = e.target.getAttribute('data-href');
                          } else {
                            document.getElementById('product-form').submit();
                          }
                        }
                    });
                }
            }
        });

        $(document).ready(function() {

        });

        window.onload = function() {
            openProductTab(event, 'Overview');
            var default_active = document.getElementById("overview-active");

            default_active.className += " active";

            var thumbList = document.getElementsByClassName('thumb-list')[0];
            var thumbFrame = document.getElementsByClassName('thumb-frame');
            var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

            if (thumbList && productHeroImage) {

                for(let i=0; i < thumbFrame.length ; i++) {
                    thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                    thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                }

                if (screen.width > 720) {
                    thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.height = productHeroImage.offsetHeight + "px";
                }
            }

            window.onresize = function() {
                if (thumbList && productHeroImage) {

                    for(let i=0; i < thumbFrame.length; i++) {
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                        thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                    }

                    if (screen.width > 720) {
                        thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.height = productHeroImage.offsetHeight + "px";
                    }
                }
            }
        };

        function updateQunatity(operation) {
            var quantity = document.getElementById('quantity').value;

            if (operation == 'add') {
                quantity = parseInt(quantity) + 1;
            } else if (operation == 'remove') {
                if (quantity > 1) {
                    quantity = parseInt(quantity) - 1;
                } else {
                    alert('{{ __('shop::app.products.less-quantity') }}');
                }
            }
            document.getElementById("quantity").value = quantity;

            event.preventDefault();
        }
    </script>
@endpush