@extends('hone::backend._shared.forms._layouts.form_master')

@php
    $templates_list = isset($templates_list) ? $templates_list : [];
@endphp

@section('form')
    @include('hone::backend._shared.forms.elements.rte', [
        'name' => 'description',
        'label' => 'Description:'
    ])

    @include('hone::backend._shared.forms.file_picker.controls', [
        'picker_type' => 'image',
        'picker_name' => 'image',
        'picker_id' => 'image',
        'picker_label' => 'Banner Image: ',
        'picker_save' => 'id',
        'saved_value' => isset($model) ? $model->banner_image : null,
    ])
@endsection
