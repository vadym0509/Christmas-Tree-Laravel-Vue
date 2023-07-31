@extends('hone::backend._shared.model_layouts.index')

@section('page_title', $view_data['plural_name'])
@section('page_heading', $view_data['plural_name'])

@section('table_head')
    <tr>
        <th class="id-column">ID</th>
        <th>Title</th>
        <th>Controls</th>
    </tr>
@endsection

@section('table_body')
    @each($view_data['tbody_view'], $collection->items(), 'model')
@endsection
