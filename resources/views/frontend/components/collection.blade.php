<div class="collection">
    <p class="collection_title">
        {{ data_get($component->collection, 'name') }}
    </p>
    <p class="collection_subtitle">
        {{ $component->subtitle }}
    </p>
    <div class="row collection_wrap">
        @foreach(data_get($component->collection, 'relationships.products', []) as $product)
            <div class="col-lg-3 col-md-6 collection_item_wrap">
                @include('frontend.components.product-tile', ['product' => $product, 'component' => $component])
            </div>
        @endforeach
    </div>
</div>
