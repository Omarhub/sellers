<area-search></area-search>

<?php
    if (isset($address)) {
        $area = $address->area;
    } else {
        $area = '';
    }
?>

@push('scripts')

    <script type="text/x-template" id="area-search-template">

        <div>
            <div class="control-group" :class="[errors.has('area') ? 'has-error' : '']">
                <label for="search">{{ __('seller::app.shop.customer.account.address.area') }}</label>

                <input type="text" class="control" autocomplete="off" v-model="term" placeholder="{{ __('seller::app.shop.customer.account.address.search-hint') }}" v-on:keyup="search()" >

                <input type="hidden" class="control" name="area" v-model='area' v-validate="'required'" data-vv-as="&quot;{{ __('seller::app.shop.customer.account.address.area') }}&quot;" value>
                <span class="control-error" v-if="errors.has('area')">@{{ errors.first('area') }}</span>

                <span class="filter-tag" style="text-transform: capitalize; margin-right: 0px; justify-content: flex-start" v-if="area.length">
                    <span class="wrapper" style="margin-left: 0px; margin-right: 10px;">
                        @{{ area }}
                    <span class="icon cross-icon" @click="removeArea()"></span>
                    </span>
                </span>

                <div class="linked-product-search-result">
                    <ul>
                        <li v-for='(area, index) in searchArea' v-if='searchArea.length' @click="addArea(area.name)" style="color: blue;">
                            @{{ area.name }}
                        </li>

                        <li v-if="term.length > 2 && no_result">
                            {{ __('seller::app.shop.customer.account.address.no-result-found') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </script>

    <script>
        Vue.component('area-search', {

            template: '#area-search-template',

            inject: ['$validator'],

            data: function() {
                return {
                    areas: @json(app('Webkul\Seller\Repositories\AreaRepository')->all()),

                    term: "",

                    searchArea: [],

                    no_result : false,

                    area: @json($area),
                }
            },

            methods: {
                search () {
                    this_this = this;

                    if (this.term.length > 2) {
                        this_this.searchArea =  this.areas.filter(area => {
                            return area.name.toLowerCase().includes(this.term.toLowerCase())
                        })

                        if (! this_this.searchArea.length) {
                            this_this.no_result = true;
                        } else {
                            this_this.no_result = false;
                        }
                    } else {
                        this_this.searchArea = [];
                    }
                },

                addArea: function (name) {
                    this_this = this;
                    this_this.area = name;
                    this_this.term = '';
                    this_this.searchArea = [];
                },

                removeArea: function () {
                    this_this = this;
                    this_this.area = '';
                },
            }
        });
    </script>
@endpush
