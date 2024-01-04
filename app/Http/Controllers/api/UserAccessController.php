<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAccessController extends Controller
{
    protected UserAccess $userAccess;

    /**
     * @param UserAccess $userAccess
     */
    public function __construct(UserAccess $userAccess)
    {
        $this->userAccess = $userAccess;
        $this->middleware('auth:api');
    }

    public function accesses(Request $request)
    {
        $user_id = $request->user_id;
        $club_id = $request->club_id;

        $accesses = $this->userAccess->getAccess($request);
        if ($accesses == null)
        {
            $this->userAccess->updateAccess($user_id, $club_id);
            $accesses = $this->userAccess->getAccess($request);
        }
        return $accesses;
    }
    public function update(Request $request): array
    {
        $user_id = $request->user_id;
        $club_id = $request->club_id;
        $new_user = $request->new_user;
        $balance_money = $request->balance_money;
        $balance_bonus = $request->balance_bonus;
        $ret_money = $request->ret_money;
        $ret_bonus = $request->ret_bonus;
        $open_shift = $request->open_shift;
        $close_shift = $request->close_shift;
        $create_pc = $request->create_pc;
        $create_zone = $request->create_zone;
        $create_task = $request->create_task;
        $shop_pay = $request->shop_pay;
        $create_promo = $request->create_promo;
        $create_game = $request->create_game;
        $del_game = $request->del_game;
        $edit_game = $request->edit_game;
        $create_steam_acc = $request->create_steam_acc;
        $price_create = $request->price_create;
        $price_del = $request->price_del;

        $userAccess = $this->userAccess->updateUser($user_id, $club_id, $new_user, $balance_money, $balance_bonus, $ret_money,
            $ret_bonus, $open_shift, $close_shift, $create_pc, $create_zone, $create_task, $shop_pay, $create_promo,
            $create_game, $del_game, $edit_game, $create_steam_acc, $price_create, $price_del);
        if ($userAccess === 1) {
            return array('status' => 'OK');
        } else {
            return array('status' => 'Error');
        }
    }
}
