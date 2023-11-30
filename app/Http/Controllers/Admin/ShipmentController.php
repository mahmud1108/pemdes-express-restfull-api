<?php

namespace App\Http\Controllers\Admin;

use App\Helper\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditDeliveryStatusRequest;
use App\Http\Requests\ShipmentCreateRequest;
use App\Http\Resources\Courier\ShipmentCollection;
use App\Http\Resources\Courier\ShipmentCourierResource;
use App\Http\Resources\Guest\CheckReceipts;
use App\Models\Shipment;
use App\Models\Village;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class ShipmentController extends Controller
{

    private function get_shipment($no_receipts)
    {
        $shipment = Shipment::where('no_receipts', $no_receipts)->first();

        if (!$shipment) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'No found'
                    ]
                ]
            ], 404));
        }

        return $shipment;
    }

    public function create(ShipmentCreateRequest $request)
    {
        $data = $request->validated();
        $weight = $request->input('weight');
        $village = Village::where('village_id', $data['village_destination'])->first();

        if (!$village) {
            throw new HttpResponseException(response([
                'errors' => [
                    'Village not found'
                ]
            ], 404));
        }

        $cost = 0;
        if ($weight < 1000) {
            $cost = 10000;
        } else {
            $up_rounding = ceil($weight / 1000);
            $cost = 10000 * $up_rounding;
            $cost = intval($cost);
        }

        $shipment = new Shipment;
        $shipment->no_receipts = 'PE_' . Random::generate(10, '0-9');
        $shipment->senders_name = $data['senders_name'];
        $shipment->senders_phone = $data['senders_phone'];
        $shipment->senders_address = $data['senders_address'];
        $shipment->weight = $data['weight'];
        $shipment->total_cost = $cost;
        $shipment->item_name = $data['item_name'];
        $shipment->destination_address = $data['destination_address'];
        $shipment->receivers_name = $data['receivers_name'];
        $shipment->receivers_phone = $data['receivers_phone'];
        $shipment->delivery_status = $data['delivery_status'];
        $shipment->payment_status = $data['payment_status'];
        $shipment->village_destination = $data['village_destination'];
        $shipment->current_bumdes = $data['current_bumdes'];
        $shipment->date_address = $data['date_address'];
        $shipment->courier_id = 1;
        $shipment->save();

        return new ShipmentCourierResource($shipment);
    }

    public function edit_delivery_status($no_receipts, EditDeliveryStatusRequest $request)
    {
        $shipment = $this->get_shipment($no_receipts);
        $data = $request->validated();

        $shipment->delivery_status = $data['delivery_status'];
        $shipment->acknowledgment = FileHelper::instance()->upload($data['acknowledgment'], 'acknowledgment');
        $shipment->update();

        return new ShipmentCourierResource($shipment);
    }

    public function get_all_shipment(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        if ($request->input('delivery') || $request->input('payment')) {
            $shipment = Shipment::query();

            $shipment = $shipment->where(function (Builder $builder) use ($request) {
                $delivery_status = $request->input('delivery');
                if ($delivery_status) {
                    $builder->where('delivery_status', $delivery_status);
                }

                $payment_status = $request->input('payment');
                if ($payment_status) {
                    $builder->where('payment_status', $payment_status);
                }

                if ($payment_status and $delivery_status) {
                    $builder->where('delivery_status', $delivery_status)->where('payment_status', $payment_status);
                }
            });
            $shipment = $shipment->paginate(perPage: $per_page, page: $page);
        } else {
            $shipment = Shipment::paginate(perPage: $per_page, page: $page);
        }

        return new ShipmentCollection($shipment);
    }

    public function delete($no_receipts)
    {
        $shipment = $this->get_shipment($no_receipts);
        $shipment->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
