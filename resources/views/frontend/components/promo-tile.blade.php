<div class="promo">
    @foreach($component->promotions as $promotion)
        <div class="promo_item">
            @if($loop->odd)
            <div class="promo_item_img">
                <img src="{{ $promotion->imagePath }}" alt="promo">
            </div>
            @endif
            <div class="promo_item_body {{ ff() ? 'ff-theme' : '' }}">
                <p class="title">{{ $promotion->title }}</p>
                <p>{!! $promotion->body !!}</p>

                @if(!empty($promotion->cta_link) && !empty($promotion->cta_text))
            <a href="{{ $promotion->cta_link }}" class="default_button">{{ $promotion->cta_text }}</a>
                @endif
            </div>
            @if(!$loop->odd)
            <div class="promo_item_img">
                <img src="{{ $promotion->imagePath }}" alt="promo">
            </div>
            @endif
        </div>
    @endforeach
</div>
