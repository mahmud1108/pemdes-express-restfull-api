<?php

namespace App\Http\Controllers;

use App\Http\Resources\Guest\CheckReceipts;
use App\Models\Shipment;
use App\Models\Village;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function check_receipts($no_receipts)
    {
        $receipts = Shipment::where('no_receipts', $no_receipts)->first();

        if (!$receipts) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Not found'
                    ]
                ]
            ], 404));
        }

        return (new CheckReceipts($receipts))->response()->setStatusCode(200);
    }

    public function check_cost($village_id, Request $request)
    {
        $weight = $request->input('weight');
        $village = Village::where('village_id', $village_id)->first();

        if (!$village) {
            throw new HttpResponseException(response([
                'errors' => [
                    'Not found'
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

        return response()->json([
            'data' => [
                'weight' => $weight,
                'cost' => $cost,
                'destination' => $village->village_name
            ]
        ]);
    }
}
