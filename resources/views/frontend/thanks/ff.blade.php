@extends('layouts.base')

@section('css')
@endsection

@section('content')
    <div class="col-lg-12">
        {!! $banner->render() !!}

        <div class="login pl-4 pr-4">
            @if(session('status', true))
                <h3 class="login_form_heading">Thanks for your order!</h3>
                <p>We will contact you shortly to arrange your order</p>
            @else
                <h3 class="login_form_heading">Sorry, something went wrong :(</h3>
                <p>Please try again later, or give us a call at +44 (0)1577 865 500.</p>
                <p>We apologise for any inconvenience.</p>
            @endif
        </div>
    </div>
@endsection
