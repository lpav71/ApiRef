<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Promo extends Model
{
    use HasFactory;
    protected $table = 'promo';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @param mixed $promocod
     * @param mixed $description
     * @param mixed $date_start
     * @param mixed $date_stop
     * @param mixed $max_activation
     * @param mixed $num
     * @return void
     */
    public function addPromo(mixed $club_id, mixed $promocod, mixed $description, mixed $date_start, mixed $date_stop, mixed $max_activation, mixed $num): void
    {
        $promo = new Promo();
        $promo->club_id = $club_id;
        $promo->promo = $promocod;
        $promo->description = $description;
        $promo->date_start = $date_start;
        $promo->date_stop = $date_stop;
        $promo->max_activation = $max_activation;
        $promo->num = $num;
        $promo->status = true;
        $promo->save();
    }

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getAllPromo(mixed $club_id)
    {
        return Promo::where('club_id', $club_id)->get();
    }

    /**
     * @param mixed $club_id
     * @param mixed $promoCode
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getPromoCodes(mixed $club_id, mixed $promoCode)
    {
        return DB::table('promo')
            ->where('date_stop', '>', DB::raw('NOW()'))
            ->whereColumn('activations', '<', 'max_activation')
            ->where('club_id', $club_id)
            ->where('promo', $promoCode)
            ->first();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function promoDetails($promo_id, $club_id): \Illuminate\Support\Collection
    {
        return DB::table('promo_activation as t1')
            ->join('promo as t2', 't1.promo_id', '=', 't2.id')
            ->select('t1.date_d', 't2.promo', 't2.max_activation', 't2.num', 't2.activations')
            ->where('t1.promo_id', '=', $promo_id)
            ->where('t1.club_id', '=', $club_id)
            ->get();
    }
}
