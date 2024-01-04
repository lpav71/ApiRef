<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ClubSteamAccount;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    protected Game $game;

    /**
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->middleware('auth:api');
    }

    public function getGames(Request $request)
    {
        $club_id = $request->club_id;
        $games = $this->game->getGames($club_id);
        return $games;
    }

    public function changeStatus(Request $request): array
    {
        $status = $request->status;
        $id = $request->id;
        if ($status == 'true')
            $status = true;
        else $status = false;
        $this->game->changeStatus($id, $status);
        return array('status' => 'OK');
    }

    public function searchGame(Request $request)
    {
        $gameName = $request->name;
        $games = $this->game->gameSearch($gameName);
        return $games;
    }

    public function getGameById(Request $request)
    {
        $id = $request->id;
        $game = $this->game->getGamebyId($id);
        return $game;
    }

    public function saveGame(Request $request): array
    {
        $id = $request->id;
        $name = $request->name;
        $link = $request->link;
        $param = $request->param;
        $steam_id = $request->steam_id;
        $club_account = $request->club_account;
        $description = $request->description;
        $icon = $request->icon;
        $type = $request->type;
        $club_id = $request->club_id;

        $this->game->gameUpdateOrAdd($id, $name, $link, $param, $steam_id, $club_account, $description, $icon, $type, $club_id);
        return array('status' => 'OK');
    }

    public function addSteamAccount(Request $request)
    {
        $gameName = $request->gameName;
        $steamId = $request->steamId;
        $steamLogin = $request->steamLogin;
        $steamPassword = $request->steamPassword;
        $club_id = $request->club_id;

        return $this->game->addGameAccount($gameName, $steamId, $steamLogin, $steamPassword, $club_id);
    }
}
