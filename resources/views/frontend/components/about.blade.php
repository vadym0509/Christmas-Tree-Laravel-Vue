<div class="about {{ ff() ? 'ff-theme' : '' }}">
    <img class="about_logo" src="{{ $component->icon }}" alt="logo">
    <p class="about_headline {{ ff() ? 'ff-theme' : '' }}">
        {{ $component->tagline }}
    </p>
    <div class="about_text {{ ff() ? 'ff-theme' : '' }}">
        <p class="about_text_left">
            {{ $component->left_column }}
        </p>
        <p class="about_text_right">
            {{ $component->right_column }}
        </p>
    </div>
    <p class="about_footer {{ ff() ? 'ff-theme' : '' }}">The Kilted Christmas Tree Company Limited</p>
    <p class="about_footer_bottom {{ ff() ? 'ff-theme' : '' }}">Tillyochie Farm, Balado, Kinross, Scotland KY13 0NL</p>
</div>
