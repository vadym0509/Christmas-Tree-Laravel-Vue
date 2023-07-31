@extends('layouts.base')

@section('css')
@endsection

@section('content')
    <div class="col-lg-12">

        {{-- Banner Component --}}
        {!! $banner->render() !!}

        <div class="category_landing info_block">
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    {!! data_get($category, 'description') !!}
                </div>
                <div class="col-md-9 col-sm-8">
                    <div class="row collection_wrap">
                        @foreach(collect(data_get($category, 'relationships.products.data')) as $product)
                            @include('frontend.categories.item', compact('product', 'category'))
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-5">
        {!! $youMayAlsoLike->render() !!}
    </div>
@endsection

@section('scripts')
@endsection
