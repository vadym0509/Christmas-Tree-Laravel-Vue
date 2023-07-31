@extends('layouts.base')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection

@section('content')
    <div class="col-lg-12">
        <div class="cart col-lg-12">
            <div class="col-lg-12">
                <h2 class="cart_heading">Your Shopping List</h2>
                <button class="cart_close">close</button>
                <a href="{{ URL::previous() }}" class="cart_back"><i class="fas fa-chevron-left dropdown_icon"></i>continue shopping</a>
                <shopping-cart
                    :ff="{{ ff() ? 'true' : 'false'}}"
                    sage-pay-merchant-session-key="{{ $sagePayMerchantSessionKey }}"
                >
                    <template slot="cartCopy" v-html>
                        {!! $cartCopy !!}
                    </template>
                </shopping-cart>
            </div>
        </div>

        <div class="col-lg-12 mt-5">
            {!! $youMayAlsoLike->render() !!}
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ config('services.sagepay.url') }}/js/sagepay.js"></script>
@endsection
