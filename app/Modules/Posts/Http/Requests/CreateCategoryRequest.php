<?php

namespace App\Modules\Posts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'parent_id' => 'nullable|numeric',
            'pos' => 'required|min:0',
            'status' => 'required'
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
