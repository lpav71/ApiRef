<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AppPay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerPurchasesController extends Controller
{
    protected AppPay $appPay;

    /**
     * @param AppPay $appPay
     */
    public function __construct(AppPay $appPay)
    {
        $this->appPay = $appPay;
        $this->middleware('auth:api');
    }

    public function getPurchases(Request $request)
    {
        $club_id = $request->club_id;
        return $this->appPay->getPurchasesAppPay($club_id);
    }

    public function filter(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $admin_id = $request->admin_id;
        $client_id = $request->client_id;
        $club_id = $request->club_id;

        return $this->appPay->getFilter($club_id, $start_date, $end_date, $admin_id, $client_id);
    }

    public function details(Request $request)
    {
        $id = $request->id;
        return $this->appPay->getDetails($id);
    }

    public function delivery(Request $request)
    {
        $id = $request->id;
        return $this->appPay->add($id);
    }
}
