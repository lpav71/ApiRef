<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cashbox extends Model
{
    use HasFactory;

    protected $table = 'cashbox';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @param mixed $shift_num
     * @return mixed
     */
    public function getCachboxForFinanceModal(mixed $club_id, mixed $shift_num)
    {
        return Cashbox::select('cashbox.id', 'user_id', 'users.name as admin', 'finance.shift', 'cashbox.amount', 'type_operation.name', DB::raw("to_char(dt_operation, 'DD.MM.YYYY HH24:MI') as dt_operation"))
            ->join('users', 'cashbox.admin_id', '=', 'users.id')
            ->join('type_operation', 'cashbox.type_operation', '=', 'type_operation.id')
            ->join('finance', 'cashbox.shift', '=', 'finance.shift')
            ->where('finance.club_id', $club_id)
            ->where('cashbox.shift', $shift_num)
            ->get();
    }

    /**
     * @param mixed $admin
     * @param array $types1
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getRevenue(mixed $admin, array $types)
    {
        return DB::table('cashbox')
            ->select(DB::raw('sum(amount)'))
            ->where('admin_id', $admin)
            ->whereIn('type_operation', $types)
            ->first();
    }

    /**
     * @param mixed $admin
     * @param array $types1
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getAverageRevenue(mixed $admin, array $types)
    {
        return DB::table('cashbox')
            ->select(DB::raw('round(sum(amount)/count(id), 2) AS sum'))
            ->where('admin_id', $admin)
            ->whereIn('type_operation', $types)
            ->first();
    }

    /**
     * @param mixed $club_id
     * @return \Illuminate\Support\Collection
     */
    public function getCashBoxCollection(mixed $club_id): \Illuminate\Support\Collection
    {
        return DB::table('cashbox as t1')
            ->join('users as t2', 't1.admin_id', '=', 't2.id')
            ->join('clients as t3', 't1.user_id', '=', 't3.id')
            ->join('type_operation as t4', 't1.type_operation', '=', 't4.id')
            ->select(DB::raw("TO_CHAR(t1.dt_operation, 'DD.MM.YYYY HH24:MI') AS dt_operation"), 't3.login', 't2.name', 't1.amount', 't4.name as type')
            ->where('t1.club_id', $club_id)
            ->get();
    }

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getsalesCashBox(mixed $club_id)
    {
        return Cashbox::select('cashbox.id', 'users.name', 'clients.login', DB::raw("to_char(cashbox.dt_operation, 'DD.MM.YYYY HH24:MI') AS time"), 'cashbox.amount', 'type_operation.name AS bar', 'cashbox.shift')
            ->leftJoin('users', 'cashbox.admin_id', '=', 'users.id')
            ->leftJoin('clients', 'cashbox.user_id', '=', 'clients.id')
            ->leftJoin('type_operation', 'cashbox.type_operation', '=', 'type_operation.id')
            ->where('cashbox.club_id', $club_id)
            ->where('cashbox.type_operation', 7)
            ->get();
    }

    /**
     * @param mixed $id
     * @return \Illuminate\Support\Collection
     */
    public function saleCashBox(mixed $id): \Illuminate\Support\Collection
    {
        return DB::table('cashbox_list as t1')
            ->leftJoin('store as t2', 't1.product', '=', 't2.id')
            ->select('t2.product', 't1.num', 't1.price', DB::raw('t1.num * t1.price as total'))
            ->where('t1.cashbox_id', $id)
            ->get();
    }

}
