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
            'tax' => [
                'required',
                'regex:/\A\d{1,2}\.?\d{0,2}\z/',
                'min:0',
                'max:100',
            ],
            'modality_id' => [
                'required',
                'integer',
            ],
            'date_buy' => [
                'required',
                'date_format:d/m/Y',
            ],
            'date_liquidity' => [
                'required',
                'date_format:d/m/Y',
                'after_or_equal:date_buy',
            ],
            'date_due' => [
                'required',
                'date_format:d/m/Y',
                'after_or_equal:date_buy'
            ],
        ];
    }
}
