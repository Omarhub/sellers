{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}
@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

<div class="add-to-buttons">

    {{-- @include ('seller::shop.products.view.review-rating') --}}
    <div class="review-information">
        <span class="stars">

            @for ($i = 1; $i <= round($reviewHelper->getAverageRating($product)); $i++)

                <span class="icon star-icon"></span>

            @endfor
        </span>

        <span class="avgnumber">
            {{ $reviewHelper->getAverageRating($product) }}
        </span>

        <div class="total-reviewproduct mt-5">
            {{ __('shop::app.reviews.ratingreviews', [
                'rating' => $reviewHelper->getTotalRating($product),
                'review' => $reviewHelper->getTotalReviews($product)])
            }}
        </div>

    </div>

</div>

{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}