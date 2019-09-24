@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}

@if ($customAttributeValues = $productViewHelper->getAdditionalData($product))
        <div slot="body">
            <table class="full-specifications" style="margin-left: 25px; width: 470px;">

                @foreach ($customAttributeValues as $attribute)
                    <tr>
                        @if ($attribute['label'])
                            <td class="td-data">{{ $attribute['label'] }}</td>
                        @else
                            <td class="td-data">{{ $attribute['admin_name'] }}</td>
                        @endif
                            @if ($attribute['type'] == 'file' && $attribute['value'])
                                <td class="td-data">
                                    <a  href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                                        <i class="icon sort-down-icon download"></i>
                                    </a>
                                </td>
                            @elseif ($attribute['type'] == 'image' && $attribute['value'])
                                <td class="td-data">
                                    <a href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                                        <img src="{{ Storage::url($attribute['value']) }}" style="height: 20px; width: 20px;"/>
                                    </a>
                                </td>
                            @else
                                @if($attribute['value'])
                                    <td class="td-data">{{ $attribute['value'] }}</td>
                                @else
                                <td class="td-data">N/A</td>
                                @endif
                            @endif
                    </tr>
                @endforeach

            </table>
        </div>
@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}