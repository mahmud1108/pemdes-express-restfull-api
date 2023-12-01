<?php

namespace App\Http\Controllers\Courier;

use App\Helper\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courier\CourierCreateRequest;
use App\Http\Requests\Courier\CourierLoginRequest;
use App\Http\Requests\Courier\CourierRegisterRequest;
use App\Http\Requests\Courier\CourierUpdateRequest;
use App\Http\Requests\EditDeliveryStatusRequest;
use App\Http\Resources\Courier\ShipmentCollection;
use App\Http\Resources\Courier\ShipmentCourierResource;
use App\Http\Resources\CourierResource;
use App\Models\Courier;
use App\Models\Shipment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class CourierController extends Controller
{
    public function login(CourierLoginRequest $request)
    {
        $data = $request->validated();
        $courier = Courier::where('email', $data['email'])->first();
        if (!$courier || !Hash::check($data['password'], $courier->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'email or password wrong'
                    ]
                ]
            ], 400));
        }

        $courier->token = Str::uuid()->toString();
        $courier->save();

        return new CourierResource($courier);
    }

    public function update(CourierUpdateRequest $request)
    {
        $data = $request->validated();
        $courier = Courier::where('courier_id', auth()->user()->courier_id)->first();

        if (isset($data['courier_name'])) {
            $courier->courier_name = $data['courier_name'];
        }

        if (isset($data['email'])) {
            $courier->email = $data['email'];
        }

        if (isset($data['courier_phone'])) {
            $courier->courier_phone = $data['courier_phone'];
        }

        if (isset($data['address'])) {
            $courier->address = $data['address'];
        }

        if (isset($data['photo'])) {
            FileHelper::instance()->delete($courier->photo);
            $courier->photo = FileHelper::instance()->upload($data['photo'], 'courier');
        }

        if (isset($data['password'])) {
            $courier->password = Hash::make($data['password']);
        }
        $courier->save();

        return new CourierResource($courier);
    }

    public function get_all_shipment(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $shipment = Shipment::query()->where('courier_id', auth()->user()->courier_id);
        $shipment = $shipment->paginate(perPage: $per_page, page: $page);

        return new ShipmentCollection($shipment);
    }

    public function update_shipment($no_receipts, EditDeliveryStatusRequest $request)
    {
        $data = $request->validated();
        $shipment = Shipment::where('courier_id', auth()->user()->courier_id)->where('no_receipts', $no_receipts)->first();

        if (!$shipment) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Not found'
                    ]
                ]
            ], 404));
        }

        if (isset($data['payment_status'])) {
            $shipment->payment_status = $data['payment_status'];
        }

        $shipment->delivery_status = $data['delivery_status'];
        $shipment->acknowledgment = FileHelper::instance()->upload($data['acknowledgment'], 'acknowledgment');
        $shipment->save();

        return new ShipmentCourierResource($shipment);
    }

    public function register(CourierRegisterRequest $request)
    {
        $courier = new Courier;
        $courier_id =  'KU_' . Random::generate(10, '0-9');
        $cek = Courier::where('courier_id', $courier_id)->count();

        do {
            $courier_id =  'KU_' . Random::generate(10, '0-9');
        } while ($cek > 0);

        $courier->courier_id = $courier_id;
        $courier->courier_name = $request->courier_name;
        $courier->courier_phone = $request->courier_phone;
        $courier->photo = FileHelper::instance()->upload($request->photo, 'courier');
        $courier->password = Hash::make($request->password);
        $courier->address = $request->address;
        $courier->email = $request->email;
        $courier->save();

        return (new CourierResource($courier))->response()->setStatusCode(201);
    }
}
