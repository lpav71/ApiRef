<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Providers\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function perday(Request $request)
    {
        $currentHour = Carbon::now()->setTimezone('Europe/Moscow')->hour;
        $timeStart = now()->startOfDay()->addHours($currentHour);
        $timeStop = now()->endOfDay()->addHours($currentHour);

        $bookings = DB::table('booking')
            ->select(DB::raw('COUNT(*) as count'))
            ->where(function($query) use ($timeStart, $timeStop, $request) {
                $query->where('time_start', '<=', $timeStart)
                    ->where('time_stop', '>=', $timeStart)
                    ->where('club_id', $request->club_id);
            })
            ->orWhere(function($query) use ($timeStart, $timeStop, $request) {
                $query->where('time_start', '>', $timeStart)
                    ->where('time_start', '<', $timeStop)
                    ->where('time_stop', '>', $timeStop)
                    ->where('club_id', $request->club_id);
            })
            ->orWhere(function($query) use ($timeStart, $timeStop, $request) {
                $query->where('time_start', '<=', $timeStart)
                    ->where('time_stop', '>=', $timeStart)
                    ->where('time_stop', '<=', $timeStop)
                    ->where('club_id', $request->club_id);
            })
            ->groupBy('map_comp_id')
            ->get();

        $outData = [];
        $count = 0;
        for ($i = 0; $i < $currentHour; $i++) {
            if (isset($bookings[$i])) {
                $count = $bookings[$i]->count;
            }
            array_push($outData, $count);
        }
        $computerCount = Map::where('club_id', $request->club_id)->count();
        $block_1 = array(); //0.00 - 8.00
        for ($i = 0; $i < 8; $i++) {
            if (isset($outData[$i]))
                array_push($block_1, $outData[$i]);
        }
        $block_2 = array(); //8.00 - 12.00
        for ($i = 8; $i < 12; $i++) {
            if (isset($outData[$i]))
                array_push($block_2, $outData[$i]);
        }
        $block_3 = array(); //12.00 - 18.00
        for ($i = 12; $i < 18; $i++) {
            if (isset($outData[$i]))
                array_push($block_3, $outData[$i]);
        }
        $block_4 = array(); //18.00 - 0.00
        for ($i = 18; $i <= 23; $i++) {
            if (isset($outData[$i]))
                array_push($block_4, $outData[$i]);
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
        $outPercent = array($block_1_percent, $block_2_percent, $block_3_percent, $block_4_percent,);
        return $outPercent;
    }
    public function all(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:3000/api/all',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "login": "admin222"
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $resp = json_decode($response, true);

        curl_close($curl);
        return $resp;
    }
    public function test(Request $request)
    {
        $data = array(
            'user_id' => 3,
            'action' => 'update'
        );
        return Logger::create($data, 'Описание события', 'warehouse', 3);
    }
}
