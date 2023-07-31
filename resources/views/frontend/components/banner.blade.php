<div class="banner">
    <img class="banner_img" src="{{ secure_asset($component->image) }}" alt="img">
    <div class="banner_content">
        <div class="banner_content_icon">
            @if($component->shouldDisplayIcon())
                <img src="{{ secure_asset($component->icon) }}" alt="icon">
            @endif

            @if( ! is_null($component->header))
                <p>{{ $component->header }}</p>
            @endif
        </div>
        <div class="banner_content_body">
            @if($component->shouldDisplayCta())
                <a href="{{ url($component->cta_link) }}"
                    class="banner_content_body_button default_button {{ ff() ? 'ff-theme' : '' }} ">
                    {{ $component->cta_text }}
                </a>
            @endif
            <p>{{ $component->body }}</p>
        </div>
    </div>
</div>
