<a class="collection_item" href="{{ route(ff() ? 'frontend.ff.products.show' : 'frontend.products.show', data_get($product, 'slug')) }}">
    <div class="collection_item_img">
        <img src="{{ data_get($product, 'relationships.main_image.link.href') }}" alt="promo">
        <span>view</span>
    </div>
    <div class="collection_item_body">
        <p class="collection_item_body_category">{{ data_get($component->collection, 'name') }}</p>
        <p class="collection_item_body_name">{{ data_get($product, 'name') }}</p>
        <p class="collection_item_body_params">{{ data_get($product, 'sub-description') }}</p>
    </div>
</a>
