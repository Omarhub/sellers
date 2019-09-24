@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

<div class="review-info">
    <span class="stars custom-stars">

        @for ($i = 1; $i <= round($reviewHelper->getAverageRating($product)); $i++)

            <span class="icon star-icon"></span>

        @endfor
    </span>

    <span class="number" style="
        font-size: 24px;
        color: #242424;
        letter-spacing: -0.35px;
        position: absolute;
        top: 504px;
        left: 320px;">
        {{ $reviewHelper->getAverageRating($product) }}
    </span>

    <div class="total-reviews mt-5" style="
        position: absolute;
        left: 67%;
        top: 76.7%;
        font-size: 16px;
        color: #0031F0;
        letter-spacing: -0.26px;">
        {{ __('shop::app.reviews.ratingreviews', [
            'rating' => $reviewHelper->getTotalRating($product),
            'review' => $reviewHelper->getTotalReviews($product)])
        }}
    </div>

    <div class="total-reviews">
        {{-- {{ __('shop::app.products.total-reviews', ['total' => $total]) }} --}}
    </div>

</div>