<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    protected Zone $zone;

    /**
     * @param Zone $zone
     */
    public function __construct(Zone $zone)
    {
        $this->zone = $zone;
        $this->middleware('auth:api');
    }

    public function getZone(Request $request)
    {
        $clubId = $request->club_id;
        $zone = $this->zone->getZoneOrderByNum($clubId);
        return $zone;
    }
    public function addZone(Request $request)
    {
        $club_id = $request->club_id;
        $zoneName = $request->zone_name;
        $maxNum = $this->zone->getMax($club_id);
        $zone = $this->zone->add($maxNum, $club_id, $zoneName);
        return $zone;
    }
}
