<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostAddDetailBook extends FormRequest
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
            'book_title' => 'required',
            'book_description' => 'required',
            'book_authors' => 'required',
            'book_pageCount' => 'required|numeric',
            'book_language' => 'required',
            'book_isbn' => 'required|numeric',
            'condition' => 'required',
            'kind' => 'required'
        ];
    }
}
