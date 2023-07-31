@extends('layouts.base')

@section('content')
    <div class="col-lg-12">

        {{-- Banner Component --}}
        {!! $banner->render() !!}

        {{-- Promo Tile component --}}
        {!! $promo->render() !!}

        {{-- Collection Component --}}
        {!! $collection->render() !!}

        {{-- About Tile Component --}}
        @include('frontend.components.about')

    </div>
@endsection
