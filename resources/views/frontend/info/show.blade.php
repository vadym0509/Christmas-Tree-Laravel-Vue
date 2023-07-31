@extends('layouts.base')

@section('css')
@endsection

@section('content')
    <div class="col-lg-12">

        {{-- Banner Component --}}
        {!! $banner->render() !!}

        <div class="info_block">
            <p class="info_block_title">Information</p>
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <ul class="info_block_list">
                        @foreach($infoMenu as $item)
                            <li class="{{ $item->isActive ? 'active' : '' }}">
                                <a href="{{ $item->url }}">> {{ $item->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-9 col-sm-8 info_block_text">
                    {!! $page->body !!}
                </div>
            </div>
        </div>

    </div>
@endsection
