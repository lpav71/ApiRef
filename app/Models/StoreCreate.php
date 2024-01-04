<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreCreate extends Model
{
    use HasFactory;
    protected $table = 'store_create';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return \Illuminate\Support\Collection
     */
    public function allReceipts(mixed $club_id): \Illuminate\Support\Collection
    {
        return DB::table('store_create as t1')
            ->select('t2.name AS admin', 't1.dt_create AS date', 't1.num', 't1.doc', 't1.description', 't3.product', 't3.product_param')
            ->join('users as t2', 't1.admin_id', '=', 't2.id')
            ->join('store as t3', 't1.store_id', '=', 't3.id')
            ->where('t1.club_id', $club_id)
            ->get();
    }

    /**
     * @param mixed $club_id
     * @param mixed $start_date
     * @param mixed $end_date
     * @return \Illuminate\Support\Collection
     */
    public function selectNoAdmin(mixed $club_id, mixed $start_date, mixed $end_date): \Illuminate\Support\Collection
    {
        return DB::table('store_create as t1')
            ->select('t2.name AS admin', 't1.dt_create AS date', 't1.num', 't1.doc', 't1.description', 't3.product', 't3.product_param')
            ->join('users as t2', 't1.admin_id', '=', 't2.id')
            ->join('store as t3', 't1.store_id', '=', 't3.id')
            ->where('t1.club_id', $club_id)
            ->whereBetween('t1.dt_create', [$start_date, $end_date])
            ->get();
    }

    /**
     * @param mixed $club_id
     * @param mixed $admin
     * @param mixed $start_date
     * @param mixed $end_date
     * @return \Illuminate\Support\Collection
     */
    public function selectWithAdmin(mixed $club_id, mixed $admin, mixed $start_date, mixed $end_date): \Illuminate\Support\Collection
    {
        return DB::table('store_create as t1')
            ->select('t2.name AS admin', 't1.dt_create AS date', 't1.num', 't1.doc', 't1.description', 't3.product', 't3.product_param')
            ->join('users as t2', 't1.admin_id', '=', 't2.id')
            ->join('store as t3', 't1.store_id', '=', 't3.id')
            ->where('t1.club_id', $club_id)
            ->where('t2.id', $admin)
            ->whereBetween('t1.dt_create', [$start_date, $end_date])
            ->get();
    }

    /**
     * @param mixed $product
     * @param mixed $club_id
     * @param mixed $user_id
     * @param mixed $store_id
     * @return void
     */
    public function addToStore(mixed $product, mixed $club_id, mixed $user_id, mixed $store_id): void
    {
        $storeCreate = new StoreCreate();
        $storeCreate->num = $product->qty;
        $storeCreate->doc = $product->doc;
        $storeCreate->dt_create = $product->date;
        $storeCreate->description = $product->provider;
        $storeCreate->club_id = $club_id;
        $storeCreate->admin_id = $user_id;
        $storeCreate->store_id = $store_id;
        $storeCreate->save();
    }
}
