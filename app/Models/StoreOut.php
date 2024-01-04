<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreOut extends Model
{
    use HasFactory;
    protected $table = 'store_out';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return \Illuminate\Support\Collection
     */
    public function getInfo(mixed $club_id)
    {
        return DB::table('store_out as t1')
            ->select('t2.product', 't2.product_param', 't1.num', DB::raw("to_char(t1.dateout, 'DD.MM.YYYY HH24:MI') As dateout"), 't3.name as admin', 't4.name as reason')
            ->join('store as t2', 't1.store_id', '=', 't2.id')
            ->join('users as t3', 't1.admin', '=', 't3.id')
            ->join('store_operation_type as t4', 't1.store_operation_type_id', '=', 't4.id')
            ->where('t1.club_id', '=', $club_id)
            ->get();
    }

    /**
     * @param mixed $club_id
     * @param mixed $start_date
     * @param mixed $end_date
     * @param mixed $admin_id
     * @return \Illuminate\Support\Collection
     */
    public function getFilter(mixed $club_id, mixed $start_date, mixed $end_date, mixed $admin_id): \Illuminate\Support\Collection
    {
        return DB::table('store_out as t1')
            ->select('t2.product', 't2.product_param', 't1.num', DB::raw("to_char(t1.dateout, 'DD.MM.YYYY HH24:MI') As dateout"), 't3.name as admin', 't4.name as reason')
            ->join('store as t2', 't1.store_id', '=', 't2.id')
            ->join('users as t3', 't1.admin', '=', 't3.id')
            ->join('store_operation_type as t4', 't1.store_operation_type_id', '=', 't4.id')
            ->where('t1.club_id', '=', $club_id)
            ->when($start_date != null || $end_date != null, function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('t1.dateout', [$start_date, $end_date]);
            })
            ->when($admin_id != -1, function ($query) use ($admin_id) {
                return $query->where('t3.id', $admin_id);
            })
            ->get();
    }

    /**
     * @param mixed $position
     * @param mixed $num
     * @param mixed $description
     * @param mixed $user_id
     * @param mixed $club_id
     * @param mixed $type_id
     * @return bool
     */
    public function saveStore(mixed $position, mixed $num, mixed $description, mixed $user_id, mixed $club_id, mixed $type_id): bool
    {
        return DB::table('store_out')->insert([
            'store_id' => $position,
            'num' => $num,
            'description' => $description,
            'admin' => $user_id,
            'club_id' => $club_id,
            'store_operation_type_id' => $type_id
        ]);
    }
}
