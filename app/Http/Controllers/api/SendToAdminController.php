<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Map;
use App\Models\Notification;
use App\Models\SendToAdmin;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendToAdminController extends Controller
{
    protected SendToAdmin $sendToAdmin;

    /**
     * @param SendToAdmin $sendToAdmin
     */
    public function __construct(SendToAdmin $sendToAdmin)
    {
        $this->sendToAdmin = $sendToAdmin;
        $this->middleware('auth:api');
    }

    public function getMessage(Request $request)
    {
        $club_id = $request->club_id;
        return $this->sendToAdmin->message($club_id);
    }
}

