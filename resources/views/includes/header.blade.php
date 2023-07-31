<div class="header">
    <div class="old_browser">
        We no longer support this web browser. Please upgrade to a newer browser.
    </div>
    <div class="header_top {{ ff() ? 'ff-theme' : '' }}"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="nav_bar">
                    <div class="nav_bar_left">
                        @if (ff() ? Auth::check() : true)
                            @include('frontend.components.menu', [
                            'data' => [
                                0 => ['title' => 'Shop', 'items' => $categories],
                                1 => ['title' => 'Top Sellers', 'items' => $topSellers]
                            ]])
                        @endif
                    </div>
                    <div class="nav_bar_right">
                        <div class="nav_bar_right_user">
                            <a href="{{ ff() ? route('festive-floristry') : route('frontend.home') }}">
                                <i class="fas fa-home"></i>
                            </a>
                        </div>
                        <shopping-cart-panel></shopping-cart-panel>
                        @if(ff())
                            <login-button/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
