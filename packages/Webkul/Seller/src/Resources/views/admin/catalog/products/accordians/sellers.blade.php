@section('css')
    @parent
    <style>
        .modal {
            display: none;
        }
    </style>
@stop


<accordian :title="'{{ __('seller::app.admin.seller.title') }}'" :active="true">
    <div slot="body">

        <seller-search></seller-search>

    </div>
</accordian>


@push('scripts')

    <script type="text/x-template" id="seller-search-template">

        <div>
            <div class="control-group">
                <label for="search">{{ __('seller::app.admin.seller.search') }}</label>

                <input type="text" class="control" autocomplete="off" v-model="term" placeholder="{{ __('seller::app.admin.seller.seller-search-hint') }}" v-on:keyup="search()">

                <div class="linked-product-search-result">
                    <ul>
                        <li v-for='(seller, index) in searchSeller' v-if='searchSeller.length' @click="showModal(seller.id)" style="color: blue;">
                            @{{ seller.first_name }}
                        </li>

                        <li v-if="term.length > 2 && no_result">
                            {{ __('seller::app.admin.seller.no-result-found') }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="modal" id="modal">
                <form method="POST" action="{{ route('admin.seller.assign.product') }}">

                @csrf()

                <div class="modal-container">
                    <div class="modal-header">
                        <h3>{{ __('seller::app.admin.seller.products.assing-title') }}</h3>
                        <i class="icon remove-icon"  @click="closeModal()"></i>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="product_id" value="{{ $product->product->id }}">

                        <input type="hidden" name="seller_id" :value="seller_id">

                        <div class="control-group" :class="[errors.has('condition') ? 'has-error' : '']">
                            <label for="condition" class="required">{{ __('seller::app.admin.seller.products.product-condition') }}</label>
                            <select class="control" v-validate="'required'" id="condition" name="condition" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.product-condition') }}&quot;">
                                <option value="new">{{ __('seller::app.admin.seller.products.new') }}</option>
                                <option value="old">{{ __('seller::app.admin.seller.products.old') }}</option>
                            </select>
                            <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                        </div>

                        @if ($product->product->type != 'configurable')
                            <div class="control-group" :class="[errors.has('price') ? 'has-error' : '']">
                                <label for="price" class="required">{{ __('seller::app.admin.seller.products.price') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="price" name="price" value="{{ old('price') }}" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.price') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('price')">@{{ errors.first('price') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('warranty') ? 'has-error' : '']">
                                <label for="warranty" class="required">{{ __('seller::app.admin.seller.products.warranty') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="warranty" name="warranty" value="{{ old('warranty') }}" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.warranty') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('warranty')">@{{ errors.first('warranty') }}</span>
                            </div>

                            @foreach ($inventorySources as $inventorySource)

                                <div class="control-group" :class="[errors.has('inventories[{{ $inventorySource->id }}]') ? 'has-error' : '']">
                                    <label>{{ $inventorySource->name }}</label>

                                    <input type="text" v-validate="'numeric|min:0'" name="inventories[{{ $inventorySource->id }}]" class="control" value="{{ old('inventories[' . $inventorySource->id . ']') }}" data-vv-as="&quot;{{ $inventorySource->name }}&quot;"/>

                                    <span class="control-error" v-if="errors.has('inventories[{{ $inventorySource->id }}]')">@{{ errors.first('inventories[{!! $inventorySource->id !!}]') }}</span>
                                </div>

                            @endforeach

                        @else

                            <seller-variant-list></seller-variant-list>

                        @endif

                        <button type="submit" class="btn btn-lg btn-primary" style="margin-top: 20px;">
                            {{ __('seller::app.admin.seller.submit') }}
                        </button>

                    </div>
                </div>
                </form>
            <div>
        </div>

    </script>

    <script>

        Vue.component('seller-search', {

            template: '#seller-search-template',

            data: () => ({
                sellers: @json(app('Webkul\Seller\Repositories\SellerRepository')->all()),

                term: "",

                searchSeller: [],

                is_searching: false,

                no_result : false,

                seller_id : '',
            }),

            methods: {
                search () {
                    this_this = this;

                    if (this.term.length > 2) {
                        this_this.searchSeller =  this.sellers.filter(seller => {
                            return seller.first_name.toLowerCase().includes(this.term.toLowerCase())
                        })

                        if (! this_this.searchSeller.length) {
                            this_this.no_result = true;
                        } else {
                            this_this.no_result = false;
                        }
                    } else {
                        this_this.searchSeller = [];
                    }
                },

                showModal(id) {
                    this_this = this;
                    this_this.seller_id = id;
                    var modal = document.getElementById('modal');
                    modal.style.display = "block";
                },

                closeModal () {
                    var modal = document.getElementById('modal');
                    modal.style.display = "none";
                },
            }
        });
    </script>
@endpush

@if ($product->product->type == 'configurable')
@push('scripts')
    @parent

    <script type="text/x-template" id="seller-variant-list-template">
        <div class="table" style="margin-top: 20px; overflow-x: unset;">
            <table>

                <thead>
                    <tr>
                        <th class=""></th>

                        <th class="qty">{{ __('admin::app.catalog.products.qty') }}</th>

                        @foreach ($product->product->super_attributes as $attribute)
                            <th class="{{ $attribute->code }}" style="width: 150px">{{ $attribute->admin_name }}</th>
                        @endforeach

                        <th class="price" style="width: 100px;">{{ __('admin::app.catalog.products.price') }}</th>

                        <th class="price" style="width: 100px;">{{ __('seller::app.admin.seller.products.warranty') }}</th>
                    </tr>
                </thead>

                <tbody>

                    <seller-variant-item v-for='(variant, index) in variants' :variant="variant" :key="index" :index="index"></seller-variant-item>

                </tbody>

            </table>
        </div>
    </script>

    <script type="text/x-template" id="seller-variant-item-template">
        <tr>
            <td>
                <span class="checkbox">
                    <input type="checkbox" :id="variant.id" name="selected_variants[]" :value="variant.id" v-model="selected_variants[variant.id]">
                    <label :for="variant.id" class="checkbox-view"></label>
                </span>
            </td>

            <td>
                <button style="width: 100%;" type="button" class="dropdown-btn dropdown-toggle" :disabled="!selected_variants[variant.id]">
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

            <td v-for='(attribute, index) in superAttributes'>
                @{{ optionName(variant[attribute.code]) }}
            </td>

            <td>
                <div class="control-group" :class="[errors.has(variantInputName + '[price]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" :name="[variantInputName + '[price]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.price') }}&quot;" value="0" :disabled="!selected_variants[variant.id]"/>
                    <span class="control-error" v-if="errors.has(variantInputName + '[price]')">@{{ errors.first(variantInputName + '[price]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(variantInputName + '[warranty]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" :name="[variantInputName + '[warranty]']" class="control" data-vv-as="&quot;{{ __('seller::app.admin.seller.products.warranty') }}&quot;"  :value="0" :disabled="!selected_variants[variant.id]"/>
                    <span class="control-error" v-if="errors.has(variantInputName + '[warranty]')">@{{ errors.first(variantInputName + '[warranty]') }}</span>
                </div>
            </td>

        </tr>
    </script>

    <script>
        var super_attributes = @json(app('\Webkul\Product\Repositories\ProductRepository')->getSuperAttributes($product->product));
        var variants = @json($product->product->variants);

        Vue.component('seller-variant-list', {

            template: '#seller-variant-list-template',

            inject: ['$validator'],

            data: () => ({
                variants: variants,
                superAttributes: super_attributes
            })
        });

        Vue.component('seller-variant-item', {

            template: '#seller-variant-item-template',

            props: ['index', 'variant'],

            inject: ['$validator'],

            data: () => ({
                inventorySources: @json($inventorySources),
                inventories: {},
                totalQty: 0,
                superAttributes: super_attributes,
                selected_variants: {}
            }),

            computed: {
                variantInputName () {
                    return "variants[" + this.variant.id + "]";
                }
            },

            methods: {
                optionName (optionId) {
                    var optionName = '';

                    this.superAttributes.forEach (function(attribute) {
                        attribute.options.forEach (function(option) {
                            if (optionId == option.id) {
                                optionName = option.admin_name;
                            }
                        });
                    })

                    return optionName;
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

