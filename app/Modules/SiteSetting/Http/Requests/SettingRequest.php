<?php

namespace App\Modules\SiteSetting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'display_name' => 'required',
            'key' => 'required|alpha_dash|unique:settings',
            'type' => 'required',
            'group' => 'required'
        ];
    }

    /**
     * Get the validation custom message that apply to the request.
     *
     * @return array
     */
    // public function messages()
    // {
    //     return [
    //         'name.required' => 'Tên chuyên mục bắt buộc nhập',
    //         'slug.required' => 'Slug bắt buộc nhập'
    //     ];
    // }
}
