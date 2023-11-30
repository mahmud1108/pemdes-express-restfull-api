<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ShipmentCreateRequest extends FormRequest
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
            'senders_name' => ['required'],
            'senders_phone' => ['required', 'max:20'],
            'senders_address' => ['required'],
            'weight' => ['required', 'integer'],
            'item_name' => ['required'],
            'destination_address' => ['required'],
            'receivers_name' => ['required'],
            'receivers_phone' => ['required'],
            'delivery_status' => ['required', 'in:diproses,dalam pengiriman,diterima'],
            'payment_status' => ['required', 'in:telah dibayar,belum dibayar'],
            'village_destination' => ['required'],
            'current_bumdes' => ['required'],
            'date_address' => ['required'],
            'courier_id' => ['nullable'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ], 400));
    }
}
