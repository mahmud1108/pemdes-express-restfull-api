<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bumdes\BumdesCreateRequest;
use App\Http\Requests\Bumdes\BumdesUpdateRequest;
use App\Http\Resources\BumdesCollection;
use App\Http\Resources\BumdesRresource;
use App\Http\Resources\Courier\ShipmentCollection;
use App\Models\Bumdes;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class BumdesController extends Controller
{

    private function get_bumdes($bumdes_id)
    {
        $bumdes = Bumdes::where('bumdes_id', $bumdes_id)->first();

        if (!$bumdes) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Not found'
                    ]
                ]
            ], 404));
        }

        return $bumdes;
    }

    public function create(BumdesCreateRequest $request)
    {
        $data = $request->validated();

        $bumdes = new Bumdes;
        $bumdes_id =  'BD_' . Random::generate(10, '0-9');
        $cek = Bumdes::where('bumdes_id', $bumdes_id)->count();

        do {
            $bumdes_id =  'BD_' . Random::generate(10, '0-9');
        } while ($cek > 0);

        $bumdes->bumdes_id = $bumdes_id;
        $bumdes->bumdes_name = $data['bumdes_name'];
        $bumdes->bumdes_phone = $data['bumdes_phone'];
        $bumdes->email = $data['email'];
        $bumdes->password = Hash::make($data['password']);
        $bumdes->village_id = $data['village_id'];
        $bumdes->save();

        return (new BumdesRresource($bumdes))->response()->setStatusCode(201);
    }

    public function get_all(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $bumdes = Bumdes::query()->where('bumdes_id', '!=', 'null');
        $bumdes = $bumdes->paginate(perPage: $per_page, page: $page);

        return new BumdesCollection($bumdes);
    }

    public function detail($bumdes_id)
    {
        $bumdes = $this->get_bumdes($bumdes_id);

        return new BumdesRresource($bumdes);
    }

    public function delete($bumdes_id)
    {
        $bumdes = $this->get_bumdes($bumdes_id);
        $bumdes->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    public function update($bumdes_id, BumdesUpdateRequest $request)
    {
        $data = $request->validated();
        $bumdes = $this->get_bumdes($bumdes_id);

        if (isset($data['bumdes_name'])) {
            $bumdes->bumdes_name = $data['bumdes_name'];
        }

        if (isset($data['bumdes_phone'])) {
            $bumdes->bumdes_phone = $data['bumdes_phone'];
        }

        if (isset($data['email'])) {
            $bumdes->email = $data['email'];
        }

        if (isset($data['password'])) {
            $bumdes->password = Hash::make($data['password']);
        }
        $bumdes->save();

        return new BumdesRresource($bumdes);
    }

    public function shipment_destination($bumdes_id, Request $request)
    {
        $bumdes = $this->get_bumdes($bumdes_id);
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $shipment = Shipment::query()->where('bumdes_destination', $bumdes->bumdes_id);

        $shipment = $shipment->paginate(perPage: $per_page, page: $page);

        return new ShipmentCollection($shipment);
    }

    public function current_bumdes($bumdes_id, Request $request)
    {
        $bumdes = $this->get_bumdes($bumdes_id);
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $shipment = Shipment::query()->where('current_bumdes', $bumdes->bumdes_id);
        $shipment = $shipment->paginate(perPage: $per_page, page: $page);

        return new ShipmentCollection($shipment);
    }

    public function search(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $bumdes = Bumdes::query();

        $bumdes = $bumdes->where(function (Builder $builder) use ($request) {
            $name = $request->input('name');
            if ($name) {
                $builder->where('bumdes_name', 'like', '%' . $name . '%');
            }

            $bumdes_id = $request->input('bumdes_id');
            if ($bumdes_id) {
                $builder->where('bumdes_id', 'like', '%' . $name . '%');
            }
        });

        $bumdes = $bumdes->paginate(perPage: $per_page, page: $page);
        return new BumdesCollection($bumdes);
    }
}
