<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
                'size:3'
            ],
            'number' => [
                'nullable',
                'string',
                'size:3',
                Rule::requiredIf(function () use ($request) {
                    return (empty($request->code_list) && empty($request->code) && empty($request->number_lists));
                }),
            ],
            'code_list' => [
                'nullable',
                'array',
                Rule::requiredIf(function () use ($request) {
                    return (empty($request->code) && empty($request->number) && empty($request->number_lists));
                }),
                'min:1'
            ],
            'code_list.*' => [
                'string',
                'size:3',
            ],
            'number_lists' => [
                'nullable',
                'array',
                Rule::requiredIf(function () use ($request) {
                    return (empty($request->code) && empty($request->number) && empty($request->code_list));
                }),
                'min:1'
            ],
            'number_lists.*' => [
                'string',
                'size:3',
            ],
        ];
    }
}
