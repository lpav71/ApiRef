<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Map;
use Illuminate\Http\Request;

class MapController extends Controller
{
    protected Map $map;
    protected Game $game;

    /**
     * @param Map $map
     * @param Game $game
     */
    public function __construct(Map $map, Game $game)
    {
        $this->map = $map;
        $this->game = $game;
        $this->middleware('auth:api');
    }

    public function saveMapPosition(Request $request)
    {
        $comp = $request->comp;
        $club_id = $request->club_id;
        $posx = $request->posx;
        $posy = $request->posy;

        $map = $this->map->savePosition($comp, $club_id, $posx, $posy);
        return $map;
    }
    public function getMapPosition(Request $request)
    {
        $club_id = $request->club_id;

        $mapZone = $this->map->getMapZone($club_id);
        $zone = $this->map->getZone($club_id);
        $map = $this->map->getMap($club_id);
        return array(
            'zone' => $zone,
            'map' => $map,
            'mapZone' => $mapZone
        );
    }
    public function saveNewPosition(Request $request): array
    {
        $posx = $request->posx;
        $posy = $request->posy;
        $zone = $request->zone;
        $id_comp = $request->id_comp;
        $club_id = $request->club_id;

        $this->map->savePositionAndZone($posx, $posy, $zone, $id_comp, $club_id);
        return array('status' => 'OK');
    }
    public function findPCForAdd(Request $request): array
    {
        $club_id = $request->club_id;
        $zone = $request->zone;

        $comps = array();
        $pc = $this->map->getMapByZone($club_id, $zone);
        foreach ($pc as $item) {
            $comp['club_id'] = $club_id;
            $comp['map_comp_id'] = $item->id_comp;
            $comps[] = $comp;
        }
        $this->game->addNewGame($comps);
        return $comps;
    }
    public function mapUserId(Request $request)
    {
        $club_id = $request->club_id;

        $map = $this->map->getMaps($club_id);
        return $map;
    }
    public function stopSOS(Request $request)
    {
        $id = $request->id;
        $map = $this->map->getMapId($id);
        return $map->id;
    }
}
