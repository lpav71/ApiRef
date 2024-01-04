<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\StoreCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    protected StoreCreate $storeCreate;

    /**
     * @param StoreCreate $storeCreate
     */
    public function __construct(StoreCreate $storeCreate)
    {
        $this->storeCreate = $storeCreate;
        $this->middleware('auth:api');
    }

    public function allReceipts(Request $request)
    {
        $club_id = $request->club_id;
        return $this->storeCreate->allReceipts($club_id);
    }
    public function filter(Request $request)
    {
        $admin = $request->admin;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $club_id = $request->club_id;
        if ($admin == '-1') {
            return $this->storeCreate->selectNoAdmin($club_id, $start_date, $end_date);
        }
        else {
            return $this->storeCreate->selectWithAdmin($club_id, $admin, $start_date, $end_date);
        }
    }
}
