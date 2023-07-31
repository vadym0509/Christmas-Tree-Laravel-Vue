<?php

namespace App\Controls;

use App\Clients\Moltin;

class MoltinCollectionSelection
{
    public function render($additional_field_config, $additional_field_name, $model)
    {
        $collections = app(Moltin::class)->get('v2/collections');

        return view('backend.controls.moltin_dropdown', [
                'options' => collect($collections)->pluck('name', 'slug'),
        ] + compact('additional_field_config', 'additional_field_name'))->render();
    }
}
