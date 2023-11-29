<?php

namespace App\Http\Requests\Bumdes;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class BumdesCreateRequest extends FormRequest
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
            'bumdes_name' => ['required', 'min:4'],
            'bumdes_phone' => ['required', 'max:20', 'unique:bumdes'],
            'email' => ['required', 'email', 'unique:bumdes'],
            'password' => ['required', 'min:5', 'confirmed'],
            'village_id' => ['required']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ], 400));
    }
}
