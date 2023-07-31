<div class="container footer">
    <div class="row">
        <div class="col-lg-12">
            <div class="footer_top">
                <ul class="footer_social {{ ff() ? 'ff-theme' : '' }}">
                    <li>
                        <a href="#">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/kiltedchristmastree/" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/thekiltedchristmastreeco/" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="footer_content">
                <div class="row">
                    <div class="col-lg-5 col-md-6 block logo">
                        <div class="main">
                            <p>Secure payments using</p>
                            <img src="{{ secure_asset('assets/frontend/images/sage_pay.svg') }}" alt="sage_pay">
                        </div>
                        <ul>
                            <li>
                                <img src="{{ secure_asset('assets/frontend/images/mastercard.png') }}" alt="mastercard">
                            </li>
                            <li>
                                <img src="{{ secure_asset('assets/frontend/images/visa.png') }}" alt="visa">
                            </li>
                            <li>
                                <img src="{{ secure_asset('assets/frontend/images/paypal.png') }}" alt="paypal">
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 block list">
                        {!! $footerBody !!}
                    </div>
                    <div class="col-lg-2 col-md-6 block list">
                        <p class="title">Shop</p>
                        <ul>
                            @foreach($footerCategoriesMenu as $category)
                                <li>
                                    <a href="{{ $category->url }}">{{ $category->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-6 block list">
                        <p class="title">Information</p>
                        <ul>
                            @foreach($footerInfoMenu as $page)
                                <li>
                                  <a href="{{ $page->url }}">{{ $page->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer_bottom">
                <p>© Copyright 2019 The Kilted Christmas Tree Company all rights reserved.</p>
                <p>Registered in Scotland: SC303098 Data Protection No. Z1068886</p>
            </div>
        </div>
    </div>
</div>
