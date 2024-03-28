<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class CurrencyValidator
{
    const __FORMATS__ = [
        'code' => 'AED',
        'number' => '456',
        'code_list' => ['OKD', 'PLO'],
        'number_lists' => ['456', '999'],
    ];

    public function validateInput($request)
    {
        try {
            return validator($request->all(), [
                'code' => [
                    'nullable',
                    'string',
                    'size:3',
                    Rule::requiredIf(function () use ($request) {
                        return (empty($request->code_list) && empty($request->number) && empty($request->number_lists));
                    }),
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
            ])->validate();
        } catch (ValidationException $e) {
            return ['errors' => $e->errors(), 'formats' => self::__FORMATS__ ];
        }        
    }
}
