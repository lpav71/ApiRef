<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    protected Promo $promo;

    /**
     * @param Promo $promo
     */
    public function __construct(Promo $promo)
    {
        $this->promo = $promo;
        $this->middleware('auth:api');
    }

    public function save(Request $request)
    {
        $club_id = $request->club_id;
        $promocod = $request->promo;
        $description = $request->description;
        $date_start = $request->date_start;
        $date_stop = $request->date_stop;
        $max_activation = $request->max_activation;
        $num = $request->num;

        $this->promo->addPromo($club_id, $promocod, $description, $date_start, $date_stop, $max_activation, $num);
        return array('status' => 'OK');
    }

    public function get(Request $request)
    {
        $club_id = $request->club_id;
        $promocodes = $this->promo->getAllPromo($club_id);
        return $promocodes;
    }

    public function codes(Request $request)
    {
        $club_id = $request->club_id;
        $promoCode = $request->promo_code;
        $promo = $this->promo->getPromoCodes($club_id, $promoCode);
        if ($promo === null)
            $promo = array();
        return $promo;
    }

    public function details(Request $request)
    {
        $promo_id = $request->promo_id;
        $club_id = $request->club_id;
        $detail = $this->promo->promoDetails($promo_id, $club_id);
        return $detail;
    }
}
