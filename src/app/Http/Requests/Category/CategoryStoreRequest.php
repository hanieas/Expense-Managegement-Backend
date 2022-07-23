<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryStoreRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'name' => [
                'required',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('name', '=', request()->name)
                        ->where('user_id', '=', request()->user_id);
                })
            ]
        ];
    }

    /**
     * bodyParameters
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'user_id' => [
                'description' => 'The ID of owner user, No need to fill this field. This will extract automatically from token'
            ],
            'name' => [
                'description' => 'The name of the Category.',
                'example' => 'Category1'
            ],
        ];
    }
}
