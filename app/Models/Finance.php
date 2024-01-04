<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Finance extends Model
{
    use HasFactory;
    protected $table = 'finance';
    public $timestamps = false;

    protected $fillable=['open_shift', 'close_shift'];

    /**
     * @param mixed $clubId
     * @return Collection
     */
    public function getSmenaFinanceFalse(mixed $clubId): Collection
    {
        return DB::table('finance')->select(DB::raw('cash, nocash, shop_cash, shop_nocash, to_char(close_shift, \'DD.MM.YYYY HH24:MI\') As time'))
            ->where('status', 'false')->where('club_id', $clubId)
            ->orderBy('close_shift', 'desc')
            ->get();
    }

    /**
     * @param mixed $clubId
     * @return Collection
     */
    public function getSmenaFinanceTrue(mixed $clubId): Collection
    {
        return DB::table('finance')->select(DB::raw('cash, nocash, shop_cash, shop_nocash, to_char(open_shift, \'DD.MM.YYYY HH24:MI\') As time'))->where('status', 'true')->where('club_id', $clubId)->get();
    }

    /**
     * @param mixed $clubId
     * @return Model|Builder|object|null
     */
    public function getDiagramCalc(mixed $clubId)
    {
        return DB::table('finance')
            ->where('club_id', '=', $clubId)
            ->select(DB::raw('SUM(cash + nocash) as total_cash'), DB::raw('SUM(bonus) as bonus'))->first();
    }

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getFinanceStatusTrue(mixed $club_id)
    {
        return Finance::where('status', true)->where('club_id', $club_id)->get()->toArray();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function allFinance(int $club_id): Collection
    {
        return DB::table('finance')
            ->join('users', 'finance.admin_id', '=', 'users.id')
            ->select('users.name', 'shift', 'open_shift', 'close_shift', 'cash', 'cash_num', 'nocash', 'nocash_num', 'bonus', 'bonus_num',
                'return_cash', 'return_cash_num', 'return_nocash', 'return_nocash_num', 'shop_cash', 'shop_cash_num', 'shop_nocash', 'shop_nocash_num', 'status',
                DB::raw("to_char(open_shift, 'DD.MM.YYYY HH24:MI') As open_shift"), DB::raw("to_char(close_shift, 'DD.MM.YYYY HH24:MI') As close_shift"))
            ->where('finance.club_id', $club_id)
            ->get();
    }
    /**
     * @param mixed $club_id
     * @param mixed $admin_id
     * @param mixed $start_date
     * @param mixed $end_date
     * @return Collection
     */
    public function getFilterFinance(mixed $club_id, mixed $admin_id, mixed $start_date, mixed $end_date): Collection
    {
        return DB::table('finance')
            ->join('users', 'finance.admin_id', '=', 'users.id')
            ->select('users.name', 'shift', 'open_shift', 'close_shift', 'cash', 'cash_num', 'nocash', 'nocash_num', 'bonus', 'bonus_num',
                'return_cash', 'return_cash_num', 'return_nocash', 'return_nocash_num', 'shop_cash', 'shop_cash_num', 'shop_nocash', 'shop_nocash_num', 'status',
                DB::raw("to_char(open_shift, 'DD.MM.YYYY HH24:MI') As open_shift"), DB::raw("to_char(close_shift, 'DD.MM.YYYY HH24:MI') As close_shift"))
            ->where('finance.club_id', $club_id)
            ->when($admin_id != -1, function ($query) use ($admin_id) {
                return $query->where('admin_id', $admin_id);
            })
            ->whereBetween('open_shift', [$start_date, $end_date])
            ->get();
    }

    /**
     * @param mixed $user_id
     * @return \Illuminate\Support\Collection
     */
    public function getShift(mixed $user_id): \Illuminate\Support\Collection
    {
        return DB::table('finance')
            ->join('users', 'finance.admin_id', '=', 'users.id')
            ->select(DB::raw("to_char(finance.open_shift, 'DD.MM.YYYY HH24:MI') AS open_shift, to_char(finance.close_shift, 'DD.MM.YYYY HH24:MI') AS close_shift"))
            ->where('users.id', $user_id)
            ->orderBy('finance.shift', 'DESC')
            ->get();
    }
}
