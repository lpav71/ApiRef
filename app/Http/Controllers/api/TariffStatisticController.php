<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TariffStatisticController extends Controller
{
    protected Booking $booking;

    /**
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->middleware('auth:api');
    }

    public function statistic(Request $request)
    {
        $id_zone = $request->id_zone;
        if ($id_zone > 0) {
            $bookings = $this->booking->getBookingWithZone($request, $id_zone);
        }
        else {
            $bookings = $this->booking->getBookingWithoutZone($request);
        }
        return $bookings;
    }
}
