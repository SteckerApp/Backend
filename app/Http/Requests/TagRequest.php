<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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

    public function rules()
    {
        return [
            'category' => 'required|unique:tag_categories,name|max:255',
            'length' => 'required|integer|min:0',
            'width' => 'required|integer|min:0',
            'service_name' => 'required|string|max:255'
        ];
    }
}
