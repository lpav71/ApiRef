<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Zone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TariffController extends Controller
{
    protected Price $price;
    protected Zone $zone;

    /**
     * @param Price $price
     * @param Zone $zone
     */
    public function __construct(Price $price, Zone $zone)
    {
        $this->price = $price;
        $this->zone = $zone;
        $this->middleware('auth:api');
    }

    public function getTariff(Request $request)
    {
        $club_id = $request->club_id;
        $type = $request->type;
        $tariff = $this->price->getTariffByType($club_id, $type);
        return $tariff;
    }

    public function save(Request $request)
    {
        $club_id = $request->club_id;
        $name = $request->name;
        $week_day = $request->week_day;
        $id_zone = $request->id_zone;
        $booking_alive = $request->booking_alive;
        $add_block = json_decode($request->add_block);
        if (count($add_block) === 0) {
            return response()->json([
                'message' => 'Ошибка: неверный запрос',
            ], 400);
        }
        $error = false;
        $error = $this->price->priceAdd($error, $name, $club_id, $week_day, $id_zone, $booking_alive, $add_block);
        if ($error){
            return response()->json([
                'message' => 'Ошибка: неверный запрос',
            ], 400);
        }
        else
        {
            return response()->json([
                'message' => 'Успех',
            ]);
        }
    }
    public function zone(Request $request)
    {
        $club_id = $request->club_id;
        $zone = $this->zone->getAllZones($club_id);
        return $zone;
    }
}
