<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAccess extends Model
{
    use HasFactory;
    protected $table = 'user_access';
    public $timestamps = false;

    /**
     * @param mixed $user_id
     * @param mixed $club_id
     * @param mixed $new_user
     * @param mixed $balance_money
     * @param mixed $balance_bonus
     * @param mixed $ret_money
     * @param mixed $ret_bonus
     * @param mixed $open_shift
     * @param mixed $close_shift
     * @param mixed $create_pc
     * @param mixed $create_zone
     * @param mixed $create_task
     * @param mixed $shop_pay
     * @param mixed $create_promo
     * @param mixed $create_game
     * @param mixed $del_game
     * @param mixed $edit_game
     * @param mixed $create_steam_acc
     * @param mixed $price_create
     * @param mixed $price_del
     * @return int
     */
    public function updateUser(mixed $user_id, mixed $club_id, mixed $new_user, mixed $balance_money, mixed $balance_bonus, mixed $ret_money, mixed $ret_bonus, mixed $open_shift, mixed $close_shift, mixed $create_pc, mixed $create_zone, mixed $create_task, mixed $shop_pay, mixed $create_promo, mixed $create_game, mixed $del_game, mixed $edit_game, mixed $create_steam_acc, mixed $price_create, mixed $price_del): int
    {
        return DB::table('user_access')
            ->where('user_id', $user_id)
            ->where('club_id', $club_id)
            ->update([
                'new_user' => $new_user,
                'balance_money' => $balance_money,
                'balance_bonus' => $balance_bonus,
                'ret_money' => $ret_money,
                'ret_bonus' => $ret_bonus,
                'open_shift' => $open_shift,
                'close_shift' => $close_shift,
                'create_pc' => $create_pc,
                'create_zone' => $create_zone,
                'create_task' => $create_task,
                'shop_pay' => $shop_pay,
                'create_promo' => $create_promo,
                'create_game' => $create_game,
                'del_game' => $del_game,
                'edit_game' => $edit_game,
                'create_steam_acc' => $create_steam_acc,
                'price_create' => $price_create,
                'price_del' => $price_del
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getAccess(Request $request)
    {
        return DB::table('user_access')->where('club_id', $request->club_id)->where('user_id', $request->user_id)->first();
    }

    /**
     * @param mixed $user_id
     * @param mixed $club_id
     * @return bool
     */
    public function updateAccess(mixed $user_id, mixed $club_id): bool
    {
        return DB::table('user_access')->insert([
            'user_id' => $user_id,
            'club_id' => $club_id
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getByUserId($club_id, $user_id)
    {
        return UserAccess::where(['club_id' => $club_id, 'user_id' => $user_id])->first();
    }
}
