<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'booking';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return Collection
     */
    public function getAllClientsBooking(mixed $club_id): Collection
    {
        return DB::table('booking')
            ->where('booking.club_id', $club_id)
            ->where('clients.club_id', $club_id)
            ->where('zone.club_id', $club_id)
            ->where('price.club_id', $club_id)
            ->where('booking.status', 0)
            ->join(
                'clients',
                'booking.user_id',
                '=',
                'clients.id'
            )
            ->join(
                'zone',
                'booking.id_zone',
                '=',
                'zone.id'
            )
            ->join(
                'price',
                'booking.price_id',
                '=',
                'price.id'
            )
            ->select(DB::raw('booking.id, clients.phone, clients.email, price.name as tariff, zone.name as zone, booking.map_comp_id, clients.login, to_char(booking.time_start, \'HH24:MI\') as time_start, to_char(booking.time_stop, \'DD.MM.YYYY\') as date, to_char(booking.time_stop, \'HH24:MI\') as time_stop'))
            ->get();
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function cancelBookingBooking(mixed $id)
    {
        $booking = Booking::find($id);
        $booking->status = 4;
        $booking->save();
        return $booking;
    }

    /**
     * @param Carbon $time_start
     * @param Carbon $time_stop
     * @param mixed $club_id
     * @return Collection
     */
    public function getMaps(Carbon $time_start, Carbon $time_stop, mixed $club_id): Collection
    {
        return DB::table('map as t1')
            ->leftJoin('booking as t2', 't1.id_comp', '=', 't2.map_comp_id')
            ->selectRaw(
                'json_agg(row_to_json(row(t2.time_start, t2.time_stop, t2.user_id)) ORDER BY t2.time_start) AS fulldata, t1.id_comp, count(t1.club_id)'
            )
            ->where(function ($query) use ($time_start, $time_stop) {
                $query->where('t1.club_id', 1)
                    ->where('t2.time_start', '>=', $time_start)
                    ->where('t2.time_start', '<=', $time_stop);
            })
            ->orWhere(function ($query) use ($club_id) {
                $query->where('t1.club_id', $club_id)
                    ->whereNull('t2.time_start')
                    ->whereNull('t2.club_id');
            })
            ->groupBy('t1.id_comp')
            ->orderBy('t1.id_comp')
            ->get();
    }

    /**
     * @param mixed $client_id
     * @return Collection
     */
    public function getNotification(mixed $client_id): Collection
    {
        return DB::table('notification as t1')
            ->select('t1.user_id', 't2.map_comp_id', 't2.time_start', 't2.time_stop', 't2.duration', 't2.session_pause', 't3.tariff_type', 't3.name', 't3.price')
            ->join('booking as t2', 't1.description', '=', DB::raw('CAST(t2.id as text)'))
            ->join('price as t3', 't2.price_id', '=', 't3.id')
            ->where('t1.user_id', '=', $client_id)
            ->where('type', '=', 3)
            ->get();
    }

    /**
     * @param mixed $client_id
     * @return Collection
     */
    public function getNotificationUserCash(mixed $client_id): Collection
    {
        return DB::table('notification')
            ->select('notification.user_id', 'notification.description', 'notification.type', 'notification.value', 'users.name', 'notification.operation_id', 'notification.date_d')
            ->join('users', 'notification.admin_id', '=', 'users.id')
            ->where('notification.user_id', $client_id)
            ->whereIn('notification.type', [4, 6, 7, 8, 9, 10, 17])
            ->get();
    }

    /**
     * @param Carbon $now
     * @param int $i
     * @param int $club_id
     * @return int
     */
    public function getBookingPerTime(Carbon $now, int $i, int $club_id): int
    {
        $timeStart = $now->copy()->addHours($i);
        $timeStop = $timeStart->copy()->addMinutes(59);

        $bookings = DB::table('booking')
            ->select(DB::raw('COUNT(*) as count'))
            ->where(function ($query) use ($timeStart, $timeStop, $club_id) {
                $query->where('time_start', '<=', $timeStart)
                    ->where('time_stop', '>=', $timeStart)
                    ->where('club_id', $club_id);
            })
            ->orWhere(function ($query) use ($timeStart, $timeStop, $club_id) {
                $query->where('time_start', '>', $timeStart)
                    ->where('time_start', '<', $timeStop)
                    ->where('time_stop', '>', $timeStop)
                    ->where('club_id', $club_id);
            })
            ->orWhere(function ($query) use ($timeStart, $timeStop, $club_id) {
                $query->where('time_start', '<=', $timeStart)
                    ->where('time_stop', '>=', $timeStart)
                    ->where('time_stop', '<=', $timeStop)
                    ->where('club_id', $club_id);
            })
            ->groupBy('map_comp_id')
            ->get();

        return $bookings->count();
    }

    /**
     * @param Request $request
     * @param mixed $id_zone
     * @return \Illuminate\Support\Collection
     */
    public function getBookingWithZone(Request $request, mixed $id_zone): \Illuminate\Support\Collection
    {
        return DB::table('booking as t1')
            ->leftJoin('price as t2', 't1.price_id', '=', 't2.id')
            ->join('zone as t3', function ($join) use ($request) {
                $join->on('t3.num', '=', 't2.id_zone')
                    ->where('t3.club_id', '=', $request->club_id);
            })
            ->select('t1.id_zone', DB::raw('count(t1.price_id)'), 't2.name', 't2.tariff_type', 't2.week_day', 't3.name as zone')
            ->where('t1.club_id', $request->club_id)
            ->where('t1.time_start', '>', $request->time_start)
            ->where('t1.time_start', '<', $request->time_stop)
            ->where('t2.id_zone', $id_zone)
            ->groupBy('t1.id_zone', 't1.price_id', 't2.name', 't2.tariff_type', 't2.week_day', 't3.name')
            ->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getBookingWithoutZone(Request $request): \Illuminate\Support\Collection
    {
        return DB::table('booking as t1')
            ->leftJoin('price as t2', 't1.price_id', '=', 't2.id')
            ->join('zone as t3', function ($join) use ($request) {
                $join->on('t3.num', '=', 't2.id_zone')
                    ->where('t3.club_id', '=', $request->club_id);
            })
            ->select('t1.id_zone', DB::raw('count(t1.price_id)'), 't2.name', 't2.tariff_type', 't2.week_day', 't3.name as zone')
            ->where('t1.club_id', $request->club_id)
            ->where('t1.time_start', '>', $request->time_start)
            ->where('t1.time_start', '<', $request->time_stop)
            ->groupBy('t1.id_zone', 't1.price_id', 't2.name', 't2.tariff_type', 't2.week_day', 't3.name')
            ->get();
    }
}
