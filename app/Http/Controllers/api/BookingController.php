<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Map;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BookingController extends Controller
{
    protected $booking;

    /**
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->middleware('auth:api');
    }


    public function getAllClients(Request $request)
    {
        $club_id = $request->club_id;
        return $this->booking->getAllClientsBooking($club_id);
    }
    public function cancelBooking(Request $request)
    {
        $id = $request->id;
        return $this->booking->cancelBookingBooking($id);
    }
    public function draw(Request $request)
    {
        $club_id = $request->club_id;
        $time_start = $request->time_start;
        $time_start = Carbon::parse($time_start)->setTime(0, 0, 0);
        $time_stop = Carbon::parse($time_start)->setTime(23, 59, 0);

        $maps = $this->booking->getMaps($time_start, $time_stop, $club_id);

        $drawData = array();
        foreach ($maps as $map) {
            $recData = array();
            $rec = json_decode($map->fulldata, true);
            $recData['fulldata'] = $rec;
            $recData['id_comp'] = $map->id_comp;
            $drawData[] = $recData;
        }
// Обрезка time_stop до f1 даты и времени 23:59 -----------------------------------------------------------------------
        foreach ($drawData as &$item) {
            if (!is_null($item['fulldata'])) {
                foreach ($item['fulldata'] as &$data) {
                    $f1 = Carbon::parse($data['f1'])->setTime(0, 0, 0);
                    $f2 = Carbon::parse($data['f2'])->setTime(0, 0, 0);
                    if ($f1->notEqualTo($f2)) {
                        $data['f2'] = $f1->setTime(23, 59, 0)->toDateTimeString();
                    }
                }
            }
        }
// ~Обрезка time_stop до f1 даты и времени 23:59 ----------------------------------------------------------------------

// Расчет diff и offset -----------------------------------------------------------------------------------------------

        // ~Расчет diff и offset ----------------------------------------------------------------------------------------------

        return array_map(function ($draw) {
            $newDraw = [
                'id_comp' => $draw['id_comp'],
                'fulldata' => null,
            ];

            if (isset($draw['fulldata'])) {
                $newFulldata = [];

                $prevF2 = null;
                array_map(function ($data) use (&$newFulldata, &$prevF2) {
                    $newData = [
                        'f1' => $data['f1'],
                        'f2' => $data['f2'],
                        'f3' => $data['f3'],
                    ];

                    $newData['diff'] = Carbon::parse($data['f2'])->diffInMinutes(Carbon::parse($data['f1']));
                    if ($prevF2 !== null) {
                        $newData['offset'] = Carbon::parse($data['f1'])->diffInMinutes(Carbon::parse($prevF2));
                    } else {
                        $newData['offset'] = Carbon::parse($data['f1'])->diffInMinutes(Carbon::parse($data['f1'])->startOfDay());
                    }

                    $newFulldata[] = $newData;
                    $prevF2 = $data['f2'];
                }, $draw['fulldata']);

                $newDraw['fulldata'] = $newFulldata;
            }

            return $newDraw;
        }, $drawData);
    }
    public function reservations(Request $request): Collection
    {
        $client_id = $request->client_id;
        return $this->booking->getNotification($client_id);
    }
    public function userCash(Request $request): Collection
    {
        $client_id = $request->client_id;
        return $this->booking->getNotificationUserCash($client_id);
    }
    public function getAllBookingsPerDay(Request $request): array
    {
        $club_id = $request->club_id;
        $outData = array();
        $now = Carbon::now()->startOfDay();
        for ($i = 0; $i < 24; $i++) {
            $count = $this->booking->getBookingPerTime($now, $i, $club_id);
            $outData[] = $count;
        }
        return $outData;
    }
    public function getAllBookingsHours(Request $request)
    {
        $club_id = $request->club_id;
        $outData = array();
        $now = Carbon::now()->startOfDay();
        $currentHour = Carbon::now()->setTimezone('Europe/Moscow')->hour;
        for ($i = 0; $i < $currentHour; $i++) {
            $count = $this->booking->getBookingPerTime($now, $i, $club_id);
            $outData[] = $count;
        }

        $computerCount = Map::where('club_id', $request->club_id)->count();
        $block_1 = array(); //0.00 - 8.00
        for ($i = 0; $i < 8; $i++) {
            if (isset($outData[$i]))
                $block_1[] = $outData[$i];
        }
        $block_2 = array(); //8.00 - 12.00
        for ($i = 8; $i < 12; $i++) {
            if (isset($outData[$i]))
                $block_2[] = $outData[$i];
        }
        $block_3 = array(); //12.00 - 18.00
        for ($i = 12; $i < 18; $i++) {
            if (isset($outData[$i]))
                $block_3[] = $outData[$i];
        }
        $block_4 = array(); //18.00 - 0.00
        for ($i = 18; $i <= 23; $i++) {
            if (isset($outData[$i]))
                $block_4[] = $outData[$i];
        }
        $block_1_percent = 0;
        foreach ($block_1 as $b) {
            $perecent = $b / $computerCount * 100;
            $block_1_percent += $perecent;
        }
        $block_2_percent = 0;
        foreach ($block_2 as $b) {
            $perecent = $b / $computerCount * 100;
            $block_2_percent += $perecent;
        }
        $block_3_percent = 0;
        foreach ($block_3 as $b) {
            $perecent = $b / $computerCount * 100;
            $block_3_percent += $perecent;
        }
        $block_4_percent = 0;
        foreach ($block_4 as $b) {
            $perecent = $b / $computerCount * 100;
            $block_4_percent += $perecent;
        }
        return array($block_1_percent, $block_2_percent, $block_3_percent, $block_4_percent,);
    }
    public function getAllBookingsPerDayAPI(Request $request)
    {
        $booking = file_get_contents("http://45.135.165.89:6150/api/web/Booking/getAllBookingsPerDay?ClubId=$request->club_id");
        $bookingArray = json_decode($booking, true);
        return $bookingArray;
    }



}

