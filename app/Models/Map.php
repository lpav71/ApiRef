<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Map extends Model
{
    use HasFactory;
    protected $table = 'map';
    public $timestamps = false;

    /**
     * @param mixed $comp
     * @param mixed $club_id
     * @param mixed $posx
     * @param mixed $posy
     * @return mixed
     */
    public function savePosition(mixed $comp, mixed $club_id, mixed $posx, mixed $posy)
    {
        $map = Map::where('id_comp', $comp)->where('club_id', $club_id)->first();
        $map->pos_x = $posx;
        $map->pos_y = $posy;
        $map->save();
        return $map;
    }


    /**
     * @param mixed $club_id
     * @return mixed[]
     */
    public function getMapZone(mixed $club_id): array
    {
        return DB::table('zone')->rightJoin('map', function ($join) {
            $join->on('zone.club_id', '=', 'map.club_id');
            $join->on('zone.num', '=', 'map.zone');
        })->where('map.club_id', '=', $club_id)->orderBy('id_comp')->get()->toArray();
    }


    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getZone(mixed $club_id)
    {
        return Zone::where('club_id', $club_id)->get()->toArray();
    }


    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getMap(mixed $club_id)
    {
        return Map::where(['club_id' => $club_id])->orderBy('id_comp')->get()->toArray();
    }


    /**
     * @param mixed $posx
     * @param mixed $posy
     * @param mixed $zone
     * @param mixed $id_comp
     * @param mixed $club_id
     * @return void
     */
    public function savePositionAndZone(mixed $posx, mixed $posy, mixed $zone, mixed $id_comp, mixed $club_id): void
    {
        $map = new Map;
        $map->pos_x = $posx;
        $map->pos_y = $posy;
        $map->zone = $zone;
        $map->id_comp = $id_comp;
        $map->club_id = $club_id;
        $map->save();
    }


    /**
     * @param mixed $club_id
     * @param mixed $zone
     * @return mixed
     */
    public function getMapByZone(mixed $club_id, mixed $zone)
    {
        return Map::where('club_id', $club_id)->where('zone', $zone)->get();
    }


    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getMaps(mixed $club_id)
    {
        return Map::where(['club_id' => $club_id])->orderBy('id_comp')->get();
    }


    /**
     * @param mixed $id
     * @return mixed
     */
    public function getMapId(mixed $id)
    {
        $map = Map::find($id);
        if ($map != null) {
            $map->sos = false;
            $map->save();
        }
        return $map;
    }

}
