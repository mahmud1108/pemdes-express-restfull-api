<?php

namespace App\Http\Requests\Courier;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CourierUpdateRequest extends FormRequest
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
            'courier_name' => ['min:4'],
            'courier_phone' => ['max:20', Rule::unique('couriers')->ignore($this->courier_id, 'courier_id')],
            'address' => ['min:5'],
            'photo' => ['file', 'mimes:jpg,png,jpeg'],
            'email' => ['email', 'min:10',  Rule::unique('couriers')->ignore($this->courier_id, 'courier_id')],
            'password' => ['min:5', 'confirmed']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ], 400));
    }
}
