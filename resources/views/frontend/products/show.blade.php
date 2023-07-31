@extends('layouts.base')

@section('css')
@endsection

@section('content')
    <div class="col-lg-12">
        <div class="product">
            <div class="col-lg-12">
            <product :product="{{ json_encode($product,TRUE) }}" shipping-content="{{ $shippingAndReturnsCopy }}"></product>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-5">
        {!! $youMayAlsoLike->render() !!}
    </div>
@endsection

@section('scripts')
@endsection
