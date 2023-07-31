<i class="fas fa-bars nav_bar_item_mini"></i>
<div class="dropdown_wrap drop_menu_wrap">
    @foreach($data as $menu)
        <div class="nav_bar_left_item">
        <span class="nav_bar_item_default">
                <span class="item-title">{{ $menu['title'] }}</span>
                @if($menu['items'])
                    <div class="container dropdown_container drop_menu">
                        <div class="row">
                            @foreach($menu['items'] as $item)
                                <div class="col-lg-2 col-md-3 col-sm-4 block list">
                                    <p class="title">
                                        <a href="{{ $item->url }}">{{ $item->title }}</a>
                                    </p>
                                    <ul>
                                        @foreach($item->products as $product)
                                            <li>
                                                <a href="{{ $product->url }}">{{ $product->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                </div>
                @endif
            </span>
        </div>
    @endforeach
</div>
