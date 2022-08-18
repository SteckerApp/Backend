<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
            'name' => 'sometimes|string|max:255',
            'discription' => 'sometimes|nullable|string|max:255',
            'hear_about_us' => 'sometimes|nullable|string|max:255',
            'phone_number' => 'sometimes|string|max:255',
        ];
    }
}
