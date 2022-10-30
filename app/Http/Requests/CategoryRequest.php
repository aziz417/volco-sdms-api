<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        $id = request()->route('id') ?? null;
        //$data = $this->request->all() ?? null;

        $rules = [
            'name' => 'required|unique:categories,name,' . $id,
            'status' => 'required|numeric',
            'description' => 'nullable|string|max:500',
            'image'  => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ];


        return $rules;
    }
}
