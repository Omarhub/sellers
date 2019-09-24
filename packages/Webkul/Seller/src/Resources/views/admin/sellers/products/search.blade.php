@extends('seller::admin.layouts.content')

@section('page_title')
    {{ __('seller::app.admin.seller.products.search-title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                    {{ __('seller::app.admin.seller.products.search-title') }}
                </h1>
            </div>
        </div>

        <div class="page-content">

            {!! view_render_event('seller.admin.sellers.products.search.before') !!}

            <div class="form-container" style="margin-top: 40px">

                <product-search></product-search>

            </div>

            {!! view_render_event('seller.admin.sellers.products.search.after') !!}
        </div>
    </div>
@endsection

@push('scripts')

    <script type="text/x-template" id="product-search-template">

        <div class="control-group">

            <input type="hidden" value="{{$sellerId}}" id="getSellerId" name="sellerId" >
            <label for="search" style = "font-size: 20px;">{{ __('seller::app.admin.seller.products.search') }}</label>
            <input type="text" class="control dropdown-toggle" name="search" placeholder="{{ __('seller::app.admin.seller.products.search-term') }}" autocomplete="off" v-model.lazy="term" v-debounce="500"/>

            <div class="dropdown-list bottom-left product-search-list" style="top: 68px; width: 70%;">
                <div class="dropdown-container">
                    <ul>
                        <li v-if="products.length" class="table">
                            <table>
                                <tbody>
                                    <tr v-for='(product, index) in products'>
                                        <td>
                                            <img v-if="!product.base_image" src="{{ bagisto_asset('images/Default-Product-Image.png') }}"/>
                                            <img v-if="product.base_image" :src="product.base_image" width="50px" height="50px"/>
                                        </td>
                                        <td>
                                            @{{ product.name }}
                                        </td>
                                        <td>
                                            @{{ product.formated_price }}
                                        </td>
                                        <td class="last">
                                            <a :href="['{{ route('admin.seller.products.assign.create', $sellerId) }}/' + product.id ]" class="btn btn-primary btn-sm" style ="color:white;     width: 84%;">
                                                Assign Product
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>

                        <li v-if="!products.length && term.length > 2 && !is_searching">
                            {{ __('seller::app.admin.seller.products.no-result-found') }}
                        </li>

                        <li v-if="term.length < 3 && !is_searching">
                            {{ __('seller::app.admin.seller.products.enter-search-term') }}
                        </li>

                        <li v-if="is_searching">
                            {{ __('seller::app.admin.seller.products.searching') }}
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </script>

    <script>

        Vue.component('product-search', {

            template: '#product-search-template',

            data: () => ({
                products: [],

                term: "",

                is_searching: false,

                // sellerId: sellerId
            }),

            // mounted: function() {
            //     alert(this.sellerId);
            // },

            watch: {
                'term': function(newVal, oldVal) {
                    this.search()
                }
            },

            methods: {
                search () {
                    if (this.term.length > 2) {
                        this_this = this;

                        var sellerId = document.getElementById('getSellerId').value;

                        console.log(sellerId);

                        this.is_searching = true;

                        this.$http.get ("{{ route('admin.seller.products.search') }}", {
                            params: {
                                query: this.term,
                                sellerId: sellerId
                            },
                            // sellerId: this.sellerId
                            })
                            .then (function(response) {
                                this_this.products = response.data;

                                this_this.is_searching = false;
                            })

                            .catch (function (error) {
                                this_this.is_searching = false;
                            })
                    }
                },
            }
        });


    </script>

@endpush