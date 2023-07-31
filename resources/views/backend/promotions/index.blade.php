@extends('hone::backend._shared.model_layouts.index', [
    'mass_delete_route' => $view_data['routes_prefix'].'.mass_delete'
])

@section('page_title', $view_data['plural_name'])
@section('page_heading', $view_data['plural_name'])

@section('table_head')
    <tr>
        <th class="id-column">ID</th>
        <th class="delete-column"><i class="fa fa-times select-all-toggle" aria-hidden="true"></i></th>
        <th>Name</th>
        <th>Kilted Trees</th>
        <th>Festive Floristry</th>
        <th>Controls</th>
    </tr>
@endsection

@section('table_body')
    @each($view_data['tbody_view'], $collection, 'model')
@endsection
