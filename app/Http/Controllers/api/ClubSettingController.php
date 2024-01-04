<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ClubSetting;
use Illuminate\Http\Request;

class ClubSettingController extends Controller
{
    protected ClubSetting $clubSetting;

    /**
     * @param ClubSetting $clubSetting
     */
    public function __construct(ClubSetting $clubSetting)
    {
        $this->clubSetting = $clubSetting;
        $this->middleware('auth:api');
    }


    public  function disignData(Request $request)
    {
        $club_id = $request->club_id;
        $clubSetting = $this->clubSetting->getDisignData($club_id);
        return $clubSetting;
    }
}
