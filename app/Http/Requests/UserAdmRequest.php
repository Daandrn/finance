<?php  declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserAdmRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',  
                'string', 
                'regex:/^[A-Za-z\s]+$/', 
                'min:5', 
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users')->ignore($this->id)
            ],
            'adm' => [
                'required'
            ],
            'status' => [
                'required'
            ]
        ];
    }
}
