<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TitleRequest extends FormRequest
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
            'title' => [
                'required',
                'min:5',
                'max:100',
            ],
            'title_type_id' => [
                'required',
                'integer',
            ],
            'modality_id' => [
                'required',
                'integer',
            ],
            'tax' => [
                'required_unless:modality_id,4,6',
                'regex:/^\d{1,3}([\,\.]\d{1,2})?$/',
                'max:5',
            ],
            'date_buy' => [
                'required',
                'date_format:Y-m-d',
            ],
            'date_liquidity' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:date_buy',
            ],
            'date_due' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:date_buy',
            ],
            'value_buy' => [
                'required',
                'regex:/^\d{1,19}(?:[\,\.]\d{2})?$/',
                'max:21',
            ],
            'value_current' => [
                'required',
                'regex:/^\d{1,19}(?:[\,\.]\d{2})?$/',
                'max:21',
            ],
        ];
    }
}
