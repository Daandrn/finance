<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StocksRequest extends FormRequest
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
            'ticker' => [
                'required',
                'min:5',
                'max:6',
                'ascii',
            ],
            'name' => [
                'required',
                'min:10',
                'max:50',
                'string',
            ],
            'stocks_types_id' => [
                'required',
                'integer',
            ],
            'status' => [
                'required',
                'in:t,f',
            ],
        ];
    }

    public function passedValidation(): void
    {
        $this->merge([
            'stocks_types_id' => (int) $this->stocks_types_id,
            'status' => match ($this->status) {
                't' => true,
                'f' => false,
            }
        ]);

        return;
    }
}
