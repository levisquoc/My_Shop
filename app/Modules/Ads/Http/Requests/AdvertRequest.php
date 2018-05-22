<?php

namespace App\Modules\Ads\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertRequest extends FormRequest
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
        return [
            'link' => 'required',
            'image' => 'required',
            'position' => 'required',
            'publish_date' => 'required|date|after:today',
            'expiration_date' => 'required|date|after:publish_date',
            'status' => 'required'
        ];
    }
}
