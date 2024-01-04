<?php

namespace App\Http\Controllers\WebHooks;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Control;
use App\Models\Finance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function close(Request $request)
    {
        $admin_id = $request->admin_id;
        $apikey = $request->apikey;
        $club = Club::where('api_key', $apikey)->first();
        $timeZone = $club->time_zone;
        $todayWithUTC = Carbon::now()->addHours(3)->addMinutes($timeZone)->toDateTimeString();
        $finance = Finance::where('status', true)->where('shift', $request->shift)->where('admin_id', $request->admin_id)->first();
        if ($finance != null) {
            $finance->status = false;
            $finance->close_shift = $todayWithUTC;
            $finance->save();
            $control = Control::where('user_id', $admin_id)->first();
            if ($control != null){
                $control->shift_close = 'open';
                $control->save();
            }
            else {
                $c = new Control;
                $c->shift_close = 'open';
                $c->user_id = $admin_id;
                $c->save();
            }
            $response = array(
                'status' => 'OK'
            );
            return $response;
        }
    }
    public function open(Request $request)
    {
        $apikey = $request->apikey;
        $admin_id = $request->admin_id;
        $admin_name = $request->admin_name;
        $shift = $request->shift;
        $cash_box_serial = $request->cash_box_serial;
        $club = Club::where('api_key', $apikey)->first();
        if ($club != null) {
            $timeZone = (int)$club->time_zone;
            $time = Carbon::now()->addMinutes($timeZone + 180);
            $finance = new Finance;
            $finance->admin_id = $admin_id;
            $finance->admin_name = $admin_name;
            $finance->club_id = $club->id;
            $finance->shift = $shift;
            $finance->cash_box_serial = $cash_box_serial;
            $finance->status = true;
            $finance->open_shift = $time;
            $s = $finance->save();
            $control = Control::where('user_id', $admin_id)->first();
            if ($control != null){
                $control->shift_open = 'open';
                $control->save();
            }
            else {
                $c = new Control;
                $c->shift_open = 'open';
                $c->user_id = $admin_id;
                $c->save();
            }
            return $finance->id;
        }
        else
            return 0;
    }
    public function openOpenModal(Request $request)
    {
        $admin_id = $request->admin_id;
        $control = Control::where('user_id', $admin_id)->first();
        if ($control != null){
            $control->shift_open = 'open';
            $control->save();
        }
        else {
            $c = new Control;
            $c->shift_open = 'open';
            $c->user_id = $admin_id;
            $c->save();
        }
    }

    public function openCloseModal(Request $request)
    {
        $admin_id = $request->admin_id;
        $control = Control::where('user_id', $admin_id)->first();
        if ($control != null){
            $control->shift_close = 'open';
            $control->save();
        }
        else {
            $c = new Control;
            $c->shift_close = 'open';
            $c->user_id = $admin_id;
            $c->save();
        }
    }
}
