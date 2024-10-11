<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStocksMovementRequest extends FormRequest
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
            'user_id' => [
                'required',
                'integer',
            ],
            'stocks_id' => [
                'required',
                'integer',
            ],
            'movement_type_id' => [
                'required',
                'integer',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'value' => [
                'required',
                'regex:/^\d{1,19}(?:[\,\.]\d{2})?$/',
                'max:21',
            ],
            'date' => [
                'required',
                'date'
            ],
        ];
    }
}
