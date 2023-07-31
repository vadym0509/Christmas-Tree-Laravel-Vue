@extends('hone::backend._shared.sub_menus._master')

@section('actions')
    <a href="{{ route('backend.promotions.create') }}" class="btn btn-default">New</a>
@endsection
