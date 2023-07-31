@include('hone::backend._shared.forms.elements.select', [
    'name' => 'additional_fields['.$additional_field_name.']', 
    'id' => $additional_field_config['id'] ?? $additional_field_name, 
    'classes' => $additional_field_config['classes'] ?? null, 
    'label' => $additional_field_config['label'] ?? null, 
    'placeholder' => '', 
    'default' => $model->additional_fields[$additional_field_name] ?? null, 
    'options' => $options,
])
