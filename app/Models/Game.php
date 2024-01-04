<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['club_id', 'map_comp_id'];

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getGames(mixed $club_id)
    {
        return Game::where('club_id', $club_id)->where('map_comp_id', 1)->orderBy('id')->get();
    }

    /**
     * @param mixed $id
     * @param bool $status
     * @return void
     */
    public function changeStatus(mixed $id, bool $status): void
    {
        $game = Game::find($id);
        $game->status = $status;
        $game->save();
    }

    /**
     * @param mixed $gameName
     * @return mixed
     */
    public function gameSearch(mixed $gameName)
    {
        return Game::where('name', 'ILIKE', '%' . $gameName . '%')->orWhere('description', 'ILIKE', '%' . $gameName . '%')->get()->toArray();
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function getGamebyId(mixed $id)
    {
        return Game::find($id);
    }

    /**
     * @param mixed $id
     * @param mixed $name
     * @param mixed $link
     * @param mixed $param
     * @param mixed $steam_id
     * @param mixed $club_account
     * @param mixed $description
     * @param mixed $icon
     * @param mixed $type
     * @param mixed $club_id
     * @return void
     */
    public function gameUpdateOrAdd(mixed $id, mixed $name, mixed $link, mixed $param, mixed $steam_id, mixed $club_account, mixed $description, mixed $icon, mixed $type, mixed $club_id): void
    {
        if ($id !== 'undefined') {
            $game = $this->gameModel->getGamebyId($id);
            $game->name = $name == 'null' ? null : $name;
            $game->link = $link == 'null' ? null : $link;
            $game->param = $param == 'null' ? null : $param;
            $game->steam_id = $steam_id == 'null' ? null : $steam_id;
            $game->club_account = $club_account == 'null' ? null : $club_account;
            $game->description = $description == 'null' ? null : $description;
            $game->icon = $icon == 'null' ? null : $icon;
            $game->type = $type == 'null' ? null : $type;
        } else {
            $game = new Game;
            $game->club_id = $club_id;
            $game->map_comp_id = 1;
            $game->name = $name == 'undefined' ? null : $name;
            $game->link = $link == 'undefined' ? null : $link;
            $game->param = $param == 'undefined' ? null : $param;
            $game->steam_id = $steam_id == 'undefined' ? null : $steam_id;
            $game->club_account = $club_account == 'undefined' ? null : $club_account;
            $game->description = $description == 'undefined' ? null : $description;
            $game->icon = $icon == 'undefined' ? null : $icon;
            $game->type = $type == 'undefined' ? null : $type;
        }
        $game->save();
    }

    /**
     * @param mixed $gameName
     * @param mixed $steamId
     * @param mixed $steamLogin
     * @param mixed $steamPassword
     * @param mixed $club_id
     * @return mixed
     */
    public function addGameAccount(mixed $gameName, mixed $steamId, mixed $steamLogin, mixed $steamPassword, mixed $club_id): int
    {
        $account = new ClubSteamAccount();
        $account->game = $gameName;
        $account->steam_id = $steamId;
        $account->login_steam = $steamLogin;
        $account->pass_steam = $steamPassword;
        $account->club_id = $club_id;
        $account->save();
        return $account->id;
    }


    /**
     * @param array $comps
     * @return void
     */
    public function addNewGame(array $comps): void
    {
        $game = new Game;
        $game->insert($comps);
    }
}
