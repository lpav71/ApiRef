<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppPay extends Model
{
    use HasFactory;

    protected $table = 'app_pay';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return \Illuminate\Support\Collection
     */
    public function getPurchasesAppPay(mixed $club_id): \Illuminate\Support\Collection
    {
        return DB::table('app_pay')
            ->select('app_pay.id', DB::raw("to_char(pay_datetime, 'DD.MM.YYYY HH24:MI') As datetime"), 'pay_type', 'delivery', 'delivery_complete', 'product_num', 'shift', 'app_pay.amount', 'users.name', 'clients.login', 'app_pay.comp_id')
            ->join('users', 'app_pay.admin_id', '=', 'users.id')
            ->join('clients', 'app_pay.clients', '=', 'clients.id')
            ->where('app_pay.club_id', '=', $club_id)
            ->orderBy('app_pay.pay_datetime', 'desc')
            ->get();
    }

    /**
     * @param mixed $club_id
     * @param mixed $start_date
     * @param mixed $end_date
     * @param mixed $admin_id
     * @param mixed $client_id
     * @return \Illuminate\Support\Collection
     */
    public function getFilter(mixed $club_id, mixed $start_date, mixed $end_date, mixed $admin_id, mixed $client_id): \Illuminate\Support\Collection
    {
        return DB::table('app_pay')
            ->select('app_pay.id', DB::raw("to_char(pay_datetime, 'DD.MM.YYYY HH24:MI') As datetime"), 'pay_type', 'delivery', 'delivery_complete', 'product_num', 'shift', 'app_pay.amount', 'users.name', 'clients.login', 'app_pay.comp_id')
            ->join('users', 'app_pay.admin_id', '=', 'users.id')
            ->join('clients', 'app_pay.clients', '=', 'clients.id')
            ->where('app_pay.club_id', '=', $club_id)
            ->when($start_date != null || $end_date != null, function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('app_pay.pay_datetime', [$start_date, $end_date]);
            })
            ->when($admin_id != -1, function ($query) use ($admin_id) {
                return $query->where('users.id', $admin_id);
            })
            ->when($client_id != -1, function ($query) use ($client_id) {
                return $query->where('clients.id', $client_id);
            })->get();
    }

    /**
     * @param mixed $id
     * @return \Illuminate\Support\Collection
     */
    public function getDetails(mixed $id): \Illuminate\Support\Collection
    {
        return DB::table('app_pay_list as t1')
            ->join('store as t2', 't1.store_id', '=', 't2.id')
            ->select('t2.product', 't2.product_param', 't1.amount', 't1.num')
            ->where('t1.app_pay_id', '=', $id)
            ->get();
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function add(mixed $id)
    {
        $pay = AppPay::where('id', $id)->first();
        $pay->delivery_complete = true;
        $pay->delivery = false;
        $pay->pay_datetime = Carbon::now();
        $pay->save();
        return $pay;
    }
}
