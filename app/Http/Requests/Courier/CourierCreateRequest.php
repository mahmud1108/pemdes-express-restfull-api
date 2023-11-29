<?php

namespace App\Http\Requests\Courier;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourierCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'courier_name' => ['required'],
            'courier_phone' => ['required', 'unique:couriers'],
            'address' => ['required'],
            'photo' => ['required', 'file', 'mimes:jpg,png,jpeg'],
            'email' => ['email', 'required', 'unique:couriers'],
            'password' => ['required', 'min:5', 'confirmed']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ], 400));
    }
}
