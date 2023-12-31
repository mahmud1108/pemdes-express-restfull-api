<?php

namespace App\Http\Controllers\Admin;

use App\Helper\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courier\CourierCreateRequest;
use App\Http\Requests\Courier\CourierUpdateRequest;
use App\Http\Resources\Courier\ShipmentCollection;
use App\Http\Resources\Courier\ShipmentCourierResource;
use App\Http\Resources\CourierCollection;
use App\Http\Resources\CourierResource;
use App\Models\Courier;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class CourierController extends Controller
{

    private function get_courier($courier_id)
    {
        $courier = Courier::where('courier_id', $courier_id)->first();
        if (!$courier) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Not found'
                    ]
                ]
            ], 404));
        }

        return $courier;
    }

    public function create(CourierCreateRequest $request)
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

    public function delete($courier_id)
    {
        $courier = $this->get_courier($courier_id);
        FileHelper::instance()->delete($courier->photo);

        Courier::where('courier_id', $courier_id)->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    public function detail($courier_id)
    {
        $courier = $this->get_courier($courier_id);

        return new CourierResource($courier);
    }

    public function update($courier_id, CourierUpdateRequest $request)
    {
        $data = $request->validated();
        $courier = $this->get_courier($courier_id);

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

    public function list($courier_id, Request $request)
    {
        $courier = $this->get_courier($courier_id);
        $shipment = Shipment::query()->where('courier_id', $courier->courier_id);

        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $shipment = $shipment->paginate(perPage: $per_page, page: $page);

        return new ShipmentCollection($shipment);
    }

    public function search(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $courier = Courier::query()->where('courier_name', 'like', '%' . $request->input('name') . '%');
        $courier = $courier->paginate(perPage: $per_page, page: $page);

        return new CourierCollection($courier);
    }
}
