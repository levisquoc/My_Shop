<?php

namespace App\Modules\Importrss\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRssRequest extends FormRequest
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
            'rss_link' => 'required',
            'page_target' => 'required',
            'cate_id' => 'required'
        ];
    }
}
