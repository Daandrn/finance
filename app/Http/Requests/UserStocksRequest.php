<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStocksRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'stocks_id' => [
                'required',
                'integer',
            ],
            'quantity' => [
                'required',
                'regex:/^\d{1,12}(\,\d{1,2})?$/',
            ],
            'average_value' => [
                'required',
                'regex:/^\d{1,19}(?:[\,\.]\d{2})?$/',
                'max:21',
            ],
        ];
    }
}