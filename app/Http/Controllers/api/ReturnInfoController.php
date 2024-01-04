<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\StoreOut;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReturnInfoController extends Controller
{
    protected StoreOut $storeOut;

    /**
     * @param StoreOut $storeOut
     */
    public function __construct(StoreOut $storeOut)
    {
        $this->storeOut = $storeOut;
        $this->middleware('auth:api');
    }

    public function info(Request $request): Collection
    {
        $club_id = $request->club_id;
        return $this->storeOut->getInfo($club_id);
    }
    public function filter(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $admin_id = $request->admin_id;
        $club_id = $request->club_id;

        $info = $this->storeOut->getFilter($club_id, $start_date, $end_date, $admin_id);

        return $info;
    }
}
