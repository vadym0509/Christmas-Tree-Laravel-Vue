@extends('hone::backend._shared.forms._layouts.form_master')

@php
    $templates_list = isset($templates_list) ? $templates_list : [];
@endphp

@section('form')
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">

        <li role="presentation" class="active">
            <a href="#content-tab" aria-controls="content" role="tab" data-toggle="tab">Content</a>
        </li>

        {{--
        <li role="presentation">
            <a href="#seo-tab" aria-controls="seo" role="tab" data-toggle="tab">SEO</a>
        </li>
        --}}

        <li role="presentation">
            <a href="#settings-tab" aria-controls="settings" role="tab" data-toggle="tab">Settings</a>
        </li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content">

        <div role="tabpanel" class="tab-pane active" id="content-tab">

            <div class = "form-group {{ ($errors->has('title')) ? 'has-error' : ''}}">
                {!! Form::label('title', 'Title: ') !!}
                {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                <span class="help-block">{{ $errors->first('title') }}</span>
            </div>

            @include('hone::backend._shared.forms.elements.rte', [
                'name' => 'body',
                'label' => 'Body:'
            ])

            @include('hone::backend._shared.forms.file_picker.controls', [
                'picker_type' => 'image',
                'picker_name' => 'image',
                'picker_id' => 'image',
                'picker_label' => 'Image: ',
                'picker_save' => 'id',
                'saved_value' => isset($model) ? $model->image : null,
            ])
        </div>

        <div role="tabpanel" class="tab-pane" id="settings-tab">

            <div class = "form-group {{ ($errors->has('cta_link')) ? 'has-error' : ''}}">
                {!! Form::label('cta_link', 'Call To Action Button Link: ') !!}
                {!! Form::text('cta_link', null, ['class' => 'form-control', 'placeholder' => 'Redirect URL']) !!}
                <span class="text-muted small">This feature redirects the user immediately - meaning the page content will not be displayed</span>
                <span class="help-block">{{ $errors->first('cta_link') }}</span>
            </div>

            <div class = "form-group {{ ($errors->has('cta_text')) ? 'has-error' : ''}}">
                {!! Form::label('cta_text', 'Call To Action Button Text: ') !!}
                {!! Form::text('cta_text', null, ['class' => 'form-control', 'placeholder' => 'Call To Action Button Text']) !!}
                <span class="help-block">{{ $errors->first('cta_text') }}</span>
            </div>

            <div class = "form-group {{ ($errors->has('kilted-trees')) ? 'has-error' : ''}}">
                {!! Form::checkbox('kilted-trees', isset($model) ? $model['kilted-trees'] : 0) !!}
                <span class="help-block">{{ $errors->first('kilted-trees') }}</span>
                {!! Form::label('kilted-trees', 'Show on Kilted Trees') !!}
            </div>

            <div class = "form-group {{ ($errors->has('festive-floristry')) ? 'has-error' : ''}}">
                {!! Form::checkbox('festive-floristry', isset($model) ? $model['festive-floristry'] : 0, isset($model) ? $model['festive-floristry'] : 0) !!}
                <span class="help-block">{{ $errors->first('festive-floristry') }}</span>
                {!! Form::label('kilted-trees', 'Show on Festive Floristry') !!}

            </div>

        </div>

    </div><!-- tab content -->

@endsection
