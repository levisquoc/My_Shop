<?php

namespace App\Modules\Posts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'required',
            'slug' => 'required',
            // 'category' => 'required',
            'status' => 'required',
            'pos' => 'required',
            'desc' => 'required',
            'seo_title' => 'nullable',
            'seo_keyword' => 'nullable',
            'seo_desc' => 'nullable',
            'content' => 'required',
            'image' => 'max:2048',
            'pos' => 'required|min:0'
        ];
    }
}
