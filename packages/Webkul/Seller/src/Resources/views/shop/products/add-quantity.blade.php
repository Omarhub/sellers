{!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

<div class="quantity control-group" :class="[errors.has('quantity') ? 'has-error' : '']">

    <label class="required">{{ __('shop::app.products.quantity') }}</label>

    <input class="control quantity-change" value="-" style="width: 35px; border-radius: 3px 0px 0px 3px;" onclick="updateQunatity('remove')" readonly>

    <input name="quantity" id="quantity" class="control quantity-change" value="1" v-validate="'required|numeric|min_value:1'" style="width: 60px; position: relative; margin-left: -4px; margin-right: -4px; border-right: none;border-left: none; border-radius: 0px;" data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;" readonly>

    <input class="control quantity-change" value="+" style="width: 35px; padding: 0 12px; border-radius: 0px 3px 3px 0px;" onclick=updateQunatity('add') readonly>

    <span class="control-error" v-if="errors.has('quantity')">@{{ errors.first('quantity') }}</span>
</div>
{!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}