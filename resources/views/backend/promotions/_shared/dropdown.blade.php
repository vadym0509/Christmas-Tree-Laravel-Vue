{{--
@include('hone::backend.pages._shared.dropdown', [
    'name' => '', 
    'id' => '', 
    'classes' => '', 
    'label' => '', 
    'placeholder' => '', 
    'default' => '', 
    'options' => '', 
    'multiple' => '', 
])
--}}

@php
    $name = $name ?? null;
    $id = $id ?? $name;
    $classes = $classes ?? null;
    $label = $label ?? null;
    $placeholder = $placeholder ?? null;
    $default = $default ?? null;
    $options = $options ?? $page_list; // $page_list comes from view composer
    $multiple = $multiple ?? false;
@endphp

@include('hone::backend._shared.forms.elements.select', [
    'name' => $name, 
    'id' => $id, 
    'classes' => $classes, 
    'label' => $label, 
    'placeholder' => $placeholder, 
    'default' => $default, 
    'options' => $options, 
    'multiple' => $multiple, 
])
