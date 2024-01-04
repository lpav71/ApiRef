<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClubSteamAccount extends Model
{
    use HasFactory;

    protected $table = 'club_steam_account';
    public $timestamps = false;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCountLicenses(): \Illuminate\Support\Collection
    {
        return DB::table('club_steam_account')
            ->select(DB::raw('count(steam_id) as account, club_id, steam_id, game'))
            ->groupBy('club_id', 'steam_id', 'game')
            ->get();
    }

    /**
     * @param mixed $category
     * @param mixed $steam_id
     * @param mixed $club_id
     * @return ClubSteamAccount
     */
    public function getSteamAccount(mixed $category, mixed $steam_id, mixed $club_id): ClubSteamAccount
    {
        $account = new ClubSteamAccount();
        $account->game = $category;
        $account->steam_id = $steam_id;
        $account->club_id = $club_id;
        $account->save();
        return $account;
    }
}
