<?php

namespace App\Http\Controllers\Bumdes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bumdes\BumdesUpdateRequest;
use App\Http\Resources\BumdesRresource;
use App\Http\Resources\Courier\ShipmentCollection;
use App\Http\Resources\Courier\ShipmentCourierResource;
use App\Models\Bumdes;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BumdesController extends Controller
{
    public function update(BumdesUpdateRequest $request)
    {
        $data = $request->validated();
        $bumdes = Bumdes::where('bumdes_id', auth()->user()->bumdes_id)->first();

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

    public function shipment(Request $request)
    {
        $bumdes = Bumdes::where('bumdes_id', auth()->user()->bumdes_id)->first();
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $shipment = Shipment::query()->where('current_bumdes', $bumdes->bumdes_id);
        $shipment = $shipment->paginate(perPage: $per_page, page: $page);

        return new ShipmentCollection($shipment);
    }

    public function update_current_bumdes($no_receipts)
    {
        $shipment = Shipment::where('no_receipts', $no_receipts)->first();
        $shipment->current_bumdes = auth()->user()->bumdes_id;
        $shipment->save();

        return new ShipmentCourierResource($shipment);
    }

    public function detail($no_receipts)
    {
        $shipment = Shipment::where('no_receipts', $no_receipts)->where('current_bumdes', auth()->user()->bumdes_id)->first();

        return new ShipmentCourierResource($shipment);
    }

    public function logout()
    {
        $bumdes = Bumdes::find(auth()->user()->bumdes_id);

        $bumdes->token = null;
        $bumdes->save();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
