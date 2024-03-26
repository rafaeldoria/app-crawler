<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'code' => [
                'nullable',
                'string',
                'size:3',
                Rule::requiredIf(function () use ($request) {
                    return empty($request->code_list);
                }),
            ],
            'code_list' => [
                'nullable',
                'array',
                Rule::requiredIf(function () use ($request) {
                    return empty($request->code);
                }),
                'min:1'
            ],
            'code_list.*' => [
                'string',
                'size:3',
            ],
        ];
    }
}
