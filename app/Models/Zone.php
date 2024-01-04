<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Zone extends Model
{
    use HasFactory;
    protected $table = 'zone';
    public $timestamps = false;

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllZones($club_id)
    {
        return Zone::where('club_id', $club_id)->get();
    }

    /**
     * @param mixed $clubId
     * @return mixed
     */
    public function getZoneOrderByNum(mixed $clubId)
    {
        return Zone::where('club_id', $clubId)->orderBy('num')->get();
    }

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getMax(mixed $club_id)
    {
        return Zone::where('club_id', $club_id)->selectRaw('MAX(num)')->first()->max;
    }

    /**
     * @param mixed $maxNum
     * @param mixed $club_id
     * @param mixed $zoneName
     * @return Zone
     */
    public function add(mixed $maxNum, mixed $club_id, mixed $zoneName): Zone
    {
        $zone = new Zone;
        $zone->num = $maxNum + 1;
        $zone->club_id = $club_id;
        $zone->name = $zoneName;
        $zone->save();
        return $zone;
    }
}
