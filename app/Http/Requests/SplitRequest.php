<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SplitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // public function prepareForValidation()
    // {
    //     $this->merge([
    //         'grouping' => empty($this->get('grouping')) ? null : (int) $this->get('grouping'),
    //         'split' => empty($this->get('split')) ? null : (int) $this->get('split'),
    //     ]);
    // }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $array = [
            'stocks_id' => [
                'required',
                'integer',
            ],
            'date' => [
                'required',
                'date',
            ],
            'grouping' => [
                'required_if:split,null',
                'regex:/\d{1,9}?/',
                'nullable',
                'integer',
            ],
            'split' => [
                'required_if:grouping,null',
                'regex:/\d{1,9}?/',
                'nullable',
                'integer',
            ],
        ];

        return $array;
    }

    public function passedValidation(): void
    {
        $this->merge([
            'grouping' => empty($this->grouping) 
                ? null 
                : (int) $this->grouping,
            'split' => empty($this->split) 
                ? null 
                : (int) $this->split,
        ]);
    }
}
