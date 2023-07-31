<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromotionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        request()->merge([
            'kilted-trees' => request()->has('kilted-trees') ? true : false,
            'festive-floristry' => request()->has('festive-floristry') ? true : false,
        ]);

        return [
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable',
            'cta_link' => 'nullable|string',
            'cta_text' => 'nullable|string',
            'kilted-trees' => 'boolean',
            'festive-floristry' => 'boolean',
        ];
    }
}
