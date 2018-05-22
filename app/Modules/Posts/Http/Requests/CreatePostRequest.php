<?php

namespace App\Modules\Posts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'description' => 'required',
            'seo_title' => 'nullable',
            'seo_keyword' => 'nullable',
            'seo_desc' => 'nullable',
            'content' => 'required',
            'image' => 'max:2048'
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
    //         'tittle.required' => 'tiêu đề bắt buộc nhập',
    //         'slug.required' => 'Slug bắt buộc nhập',
    //         'category.required' => 'Tên chuyên mục bắt buộc nhập',
    //         'status.required' => 'Trạng thái bắt buộc nhập',
    //         'desc.required' => 'Mô tả bắt buộc nhập',
    //         'seo_title' => 'Seo tiêu đề bắt buộc nhập',
    //         'seo_keyword.required' => 'Seo từ khóa bắt buộc nhập',
    //         'seo_desc.required' => 'Seo mô tả bắt buộc nhập',
    //         'content.required' => 'Nội dung bắt buộc nhập',
    //         'image' => 'required'
    //     ];
    // }
}
