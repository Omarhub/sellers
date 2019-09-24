@extends('seller::admin.layouts.content')

@section('page_title')
    {{ __('seller::app.admin.seller.products.assing-edit-title') }}
@stop

@section('content')
    <div class="content">

            <form method="POST" action="" enctype="multipart/form-data" @submit.prevent="onSubmit">

                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                            {{ __('seller::app.admin.seller.products.assing-edit-title') }}
                        </h1>
                    </div>

                    <div class="page-action">
                        <button type="submit" class="btn btn-lg btn-primary">
                            {{ __('seller::app.admin.seller.products.save-product-btn-title') }}
                        </button>
                    </div>
                </div>

                {!! view_render_event('admin.sellers.product.edit-assign.before') !!}

                <div class="account-table-content">

                    @csrf()

                    <div class="product-information" style="display: inline-flex;">

                        <div class="product-image">
                            <img src="{{ $product->product->base_image_url ?: bagisto_asset('images/product/meduim-product-placeholder.png') }}" style="width: 300px; height: 350px;"/>
                        </div>

                        <div class="product-details" style="padding-left: 20px;">
                            <div class="product-name" style="padding-bottom: 5px;">
                                <a href="{{ url()->to('/').'/products/'.$product->product->url_key }}" target="_blank" title="{{ $product->product->name }}">
                                    <span style="font-size: 24px;">
                                        {{ $product->product->name }}
                                    </span>
                                </a>
                            </div>

                            {{-- <div style="font-size: 20px;">
                                @include ('shop::products.price', ['product' => $product->product])
                            </div> --}}
                        </div>

                    </div>

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('seller::app.admin.seller.products.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('condition') ? 'has-error' : '']">
                                <label for="condition" class="required">{{ __('seller::app.admin.seller.products.product-condition') }}</label>

                                <?php $selectedOption = old('condition') ?: $product->condition ?>

                                <select class="control" v-validate="'required'" id="condition" name="condition" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.product-condition') }}&quot;">
                                    <option value="new" {{ $selectedOption == 'new' ? 'selected' : '' }}>{{ __('seller::app.admin.seller.products.new') }}</option>
                                    <option value="old" {{ $selectedOption == 'old' ? 'selected' : '' }}>{{ __('seller::app.admin.seller.products.old') }}</option>
                                </select>
                                <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('price') ? 'has-error' : '']">
                                <label for="price" class="required">{{ __('seller::app.admin.seller.products.price') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="price" name="price" value="{{ old('price') ?: $product->price }}" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.price') }}&quot;" {{ $product->product->type == 'configurable' ? 'disabled' : '' }}/>
                                <span class="control-error" v-if="errors.has('price')">@{{ errors.first('price') }}</span>
                            </div>

                            {{-- <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                                <label for="description" class="required">{{ __('seller::app.admin.seller.products.description') }}</label>
                                <textarea v-validate="'required'" class="control" id="description" name="description" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.description') }}&quot;">{{ old('description') ?: $product->description }}</textarea>
                                <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                            </div> --}}

                            @if ($product->product->type != 'configurable')
                                <div class="control-group" :class="[errors.has('warranty') ? 'has-error' : '']">
                                    <label for="warranty" class="required">{{ __('seller::app.admin.seller.products.warranty') }}</label>
                                    <input type="text" v-validate="'required'" class="control" id="warranty" name="warranty" value="{{ old('warranty') ?: $product->warranty }}" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.warranty') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('warranty')">@{{ errors.first('warranty') }}</span>
                                </div>
                            @endif

                            {{-- <div>
                                <input type="hidden" value="{{$product->seller_id}}" name="seller_id">
                            </div> --}}

                        </div>
                    </accordian>

                    {{-- <accordian :title="'{{ __('seller::app.admin.seller.products.images') }}'" :active="true">
                        <div slot="body">

                            <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :images='@json($product->images)'></image-wrapper>
                        </div>
                    </accordian> --}}

                    @if ($product->product->type != 'configurable')
                        <accordian :title="'{{ __('seller::app.admin.seller.products.inventory') }}'" :active="true">
                            <div slot="body">

                                @foreach ($inventorySources as $inventorySource)

                                    <?php

                                        $qty = 0;
                                        foreach ($product->product->inventories as $inventory) {
                                            if ($inventory->inventory_source_id == $inventorySource->id && $inventory->vendor_id == $product->id) {
                                                $qty = $inventory->qty;
                                                break;
                                            }
                                        }

                                        $qty = old('inventories[' . $inventorySource->id . ']') ?: $qty;

                                    ?>

                                    <div class="control-group" :class="[errors.has('inventories[{{ $inventorySource->id }}]') ? 'has-error' : '']">
                                        <label>{{ $inventorySource->name }}</label>

                                        <input type="text" v-validate="'numeric|min:0'" name="inventories[{{ $inventorySource->id }}]" class="control" value="{{ $qty }}" data-vv-as="&quot;{{ $inventorySource->name }}&quot;"/>

                                        <span class="control-error" v-if="errors.has('inventories[{{ $inventorySource->id }}]')">@{{ errors.first('inventories[{!! $inventorySource->id !!}]') }}</span>
                                    </div>

                                @endforeach
                                <input type="hidden" value="{{$product->product_id}}" name="product_id">
                                <input type="hidden" value="{{$product->seller_id}}" name="seller_id">
                                <input type="hidden" value="{{$product->id}}" name="seller_product_id">
                            </div>
                        </accordian>
                    @endif

                    @if ($product->product->type == 'configurable')
                        <accordian :title="'{{ __('seller::app.admin.seller.products.variations') }}'" :active="true">
                            <div slot="body">

                                <variant-list></variant-list>

                                <input type="hidden" value="{{$product->product_id}}" name="product_id">
                                <input type="hidden" value="{{$product->seller_id}}" name="seller_id">
                                <input type="hidden" value="{{$product->id}}" name="seller_product_id">
                            </div>
                        </accordian>
                    @endif

                </div>

                {!! view_render_event('admin.sellers.product.edit-assign.after') !!}

            </form>
    </div>
@endsection

@if ($product->product->type == 'configurable')
    @push('scripts')
        @parent

        <script type="text/x-template" id="variant-list-template">
            <div class="table" style="margin-top: 20px; overflow-x: unset;">
                <table>

                    <thead>
                        <tr>
                            <th class=""></th>

                            <th>{{ __('admin::app.catalog.products.name') }}</th>

                            <th class="qty">{{ __('admin::app.catalog.products.qty') }}</th>

                            @foreach ($product->product->super_attributes as $attribute)
                                <th class="{{ $attribute->code }}" style="width: 150px">{{ $attribute->admin_name }}</th>
                            @endforeach

                            <th class="price" style="width: 100px;">{{ __('admin::app.catalog.products.price') }}</th>

                            <th class="price" style="width: 100px;">{{ __('seller::app.admin.seller.products.warranty') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                        <variant-item v-for='(variant, index) in variants' :variant="variant" :key="index" :index="index"></variant-item>

                    </tbody>

                </table>
            </div>
        </script>

        <script type="text/x-template" id="variant-item-template">
            <tr>
                <td>
                    <span class="checkbox">
                        <input type="checkbox" :id="variant.id" name="selected_variants[]" :value="variant.id" v-model="selected_variant">
                        <label :for="variant.id" class="checkbox-view"></label>
                    </span>
                </td>

                <td data-value="{{ __('admin::app.catalog.products.name') }}">
                    @{{ variant.name }}
                </td>

                <td data-value="{{ __('admin::app.catalog.products.qty') }}">
                    <button style="width: 100%;" type="button" class="dropdown-btn dropdown-toggle" :disabled="!selected_variant">
                        @{{ totalQty }}
                        <i class="icon arrow-down-icon"></i>
                    </button>

                    <div class="dropdown-list">
                        <div class="dropdown-container">
                            <ul>
                                <li v-for='(inventorySource, index) in inventorySources'>
                                    <div class="control-group" :class="[errors.has(variantInputName + '[inventories][' + inventorySource.id + ']') ? 'has-error' : '']">
                                        <label>@{{ inventorySource.name }}</label>
                                        <input type="text" v-validate="'numeric|min:0'" :name="[variantInputName + '[inventories][' + inventorySource.id + ']']" v-model="inventories[inventorySource.id]" class="control" v-on:keyup="updateTotalQty()" :data-vv-as="'&quot;' + inventorySource.name  + '&quot;'"/>
                                        <span class="control-error" v-if="errors.has(variantInputName + '[inventories][' + inventorySource.id + ']')">@{{ errors.first(variantInputName + '[inventories][' + inventorySource.id + ']') }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>

                <td v-for='(attribute, index) in superAttributes' :data-value="attribute.admin_name">
                    @{{ optionName(variant[attribute.code]) }}
                </td>

                <td data-value="{{ __('admin::app.catalog.products.price') }}">
                    <div class="control-group" :class="[errors.has(variantInputName + '[price]') ? 'has-error' : '']">
                        <input type="text" v-validate="'required'" :name="[variantInputName + '[price]']" class="control" :value="price" data-vv-as="&quot;{{ __('admin::app.catalog.products.price') }}&quot;" :disabled="!selected_variant"/>
                        <span class="control-error" v-if="errors.has(variantInputName + '[price]')">@{{ errors.first(variantInputName + '[price]') }}</span>
                    </div>
                </td>

                <td data-value="{{ __('seller::app.admin.seller.products.warranty') }}">
                    <div class="control-group" :class="[errors.has(variantInputName + '[warranty]') ? 'has-error' : '']">
                        <input type="text" v-validate="'required'" :name="[variantInputName + '[warranty]']" class="control" :value="warranty" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.warranty') }}&quot;" :disabled="!selected_variant"/>
                        <span class="control-error" v-if="errors.has(variantInputName + '[warranty]')">@{{ errors.first(variantInputName + '[warranty]') }}</span>
                    </div>
                </td>

            </tr>
        </script>

        <script>
            var super_attributes = @json(app('\Webkul\Product\Repositories\ProductRepository')->getSuperAttributes($product->product));
            var variants = @json($product->product->variants()->with(['inventories'])->get());
            var assignVariants = @json($product->variants);

            Vue.component('variant-list', {

                template: '#variant-list-template',

                inject: ['$validator'],

                data: () => ({
                    variants: variants,
                    assignVariants: assignVariants,
                    superAttributes: super_attributes
                }),

                created () {
                    this_this = this;

                    this.variants.forEach(function(variant) {
                        this_this.assignVariants.forEach(function(assignVariant) {
                            if (variant.id == assignVariant.product_id) {
                                variant.assignVariant = assignVariant;
                            }
                        });
                    });
                },
            });

            Vue.component('variant-item', {

                template: '#variant-item-template',

                props: ['index', 'variant'],

                inject: ['$validator'],

                data: () => ({
                    inventorySources: @json($inventorySources),
                    inventories: {},
                    totalQty: 0,
                    superAttributes: super_attributes,
                    selected_variant: false,
                }),

                created () {
                    var this_this = this;

                    if (this.variant.assignVariant) {
                        this.selected_variant = this.variant.assignVariant.id;
                    }

                    this.inventorySources.forEach(function(inventorySource) {
                        this_this.inventories[inventorySource.id] = this_this.sourceInventoryQty(inventorySource.id)
                        this_this.totalQty += parseInt(this_this.inventories[inventorySource.id]);
                    })
                },

                computed: {
                    variantInputName () {
                        return "variants[" + this.variant.id + "]";
                    },

                    price () {
                        if (this.variant.assignVariant) {
                            return this.variant.assignVariant.price;
                        }

                        return 0;
                    },

                    warranty () {
                        if (this.variant.assignVariant) {
                            return this.variant.assignVariant.warranty;
                        }

                        return 0;
                    }
                },

                methods: {
                    optionName (optionId) {
                        var optionName = '';

                        this.superAttributes.forEach(function(attribute) {
                            attribute.options.forEach(function(option) {
                                if (optionId == option.id) {
                                    optionName = option.admin_name;
                                }
                            });
                        })

                        return optionName;
                    },

                    sourceInventoryQty (inventorySourceId) {
                        if (this.selected_variant) {
                            var seller_id = this.selected_variant
                        } else {
                            var seller_id = "{{ $product->seller_id }}";
                        }

                        var inventories = this.variant.inventories.filter(function(inventory) {
                            return inventorySourceId === inventory.inventory_source_id && inventory.vendor_id == seller_id;
                        })

                        if (inventories.length)
                            return inventories[0]['qty'];

                        return 0;
                    },

                    updateTotalQty () {
                        this.totalQty = 0;
                        for (var key in this.inventories) {
                            this.totalQty += parseInt(this.inventories[key]);
                        }
                    }
                }

            });
        </script>
    @endpush
@endif