@extends('layouts.base')

@section('content')
    <div class="col-lg-12">
        {!! $banner->render() !!}

        <div class="login pl-4 pr-4">
            @if(session('status', true))
                <h3 class="login_form_heading">Thanks for your order!</h3>
                <p>You will receive an order confirmation and delivery tracking information via email.</p>
            @else
                <h3 class="login_form_heading">Sorry, your payment has failed.(</h3>
                <p>Please try again later, or give us a call +44 (0)1577 865 500.</p>
            @endif
        </div>
    </div>
@endsection
