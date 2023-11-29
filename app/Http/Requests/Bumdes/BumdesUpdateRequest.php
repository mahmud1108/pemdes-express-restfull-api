<?php

namespace App\Http\Requests\Bumdes;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BumdesUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bumdes_name' => ['min:4'],
            'bumdes_phone' => ['max:20', Rule::unique('bumdes')->ignore($this->bumdes_id, 'bumdes_id')],
            'email' => ['email', Rule::unique('bumdes')->ignore($this->bumdes_id, 'bumdes_id')],
            'password' => ['min:5', 'confirmed'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ], 400));
    }
}
