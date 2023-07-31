@php
    $products = $component->products ?: [];
@endphp

<div class="{{ ($component->hidden == true) ? 'hide' : '' }}">
    @include('frontend.components.page-divider')
</div>

<div class="collection {{ ($component->hidden == true) ? 'hide' : '' }}">
    <p class="collection_subtitle">
        You may also like
    </p>
    <div class="row collection_wrap">
        @foreach($products as $product)
            <div class="col-lg-3 col-md-6 collection_item_wrap">
                @include('frontend.components.product-tile', ['product' => $product, 'component' => $component])
            </div>
        @endforeach
    </div>
</div>
