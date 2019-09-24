
<section class="review custome" >

    <div class="review-layouter">
        @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
        @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
        @inject ('priceHelper', 'Webkul\Product\Helpers\Price')

        <?php $productBaseImage = $productImageHelper->getProductBaseImage($product); ?>



        <div class="review-form">
            <div class="ratings-reviews mt-35">
                <div class="right-side">

                    @foreach ($reviewHelper->getPercentageRating($product) as $key => $count)
                        <div class="rater 5star">
                            <div class="rate-number" id={{ $key }}{{ __('shop::app.reviews.id-star')  }}></div>
                            <div class="star-name">{{ __('shop::app.reviews.star') }}</div>
                            <div class="line-bar">
                                <div class="line-value" id="{{ $key }}"></div>
                            </div>
                            <div class="percentage">
                                <span>
                                    {{ __('shop::app.reviews.percentage', ['percentage' => $count]) }}
                                </span>
                            </div>
                        </div>
                        <br/>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')

    <script>

        window.onload = (function() {
            var percentage = {};
            <?php foreach ($reviewHelper->getPercentageRating($product) as $key => $count) { ?>

                percentage = <?php echo "'$count';"; ?>
                id = <?php echo "'$key';"; ?>
                idNumber = id + 'star';

                document.getElementById(id).style.width = percentage + "%";
                document.getElementById(id).style.height = 4 + "px";
                document.getElementById(idNumber).innerHTML = id ;

            <?php } ?>
        })();

    </script>

@endpush