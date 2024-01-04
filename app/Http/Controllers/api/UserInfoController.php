<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    protected Finance $finance;

    /**
     * @param Finance $finance
     */
    public function __construct(Finance $finance)
    {
        $this->finance = $finance;
        $this->middleware('auth:api');
    }

    public function shifts(Request $request)
    {
        $user_id = $request->user_id;
        return $this->finance->getShift($user_id);
    }
}
