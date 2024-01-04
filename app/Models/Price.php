<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Price extends Model
{
    use HasFactory;

    protected $table = 'price';
    public $timestamps = false;

    /**
     * @param bool $error
     * @param mixed $name
     * @param mixed $club_id
     * @param mixed $week_day
     * @param mixed $id_zone
     * @param mixed $booking_alive
     * @param mixed $add_block
     * @return bool
     */
    public function priceAdd(bool $error, mixed $name, mixed $club_id, mixed $week_day, mixed $id_zone, mixed $booking_alive, mixed $add_block): bool
    {
        try {
            DB::transaction(function () use (&$error, $name, $club_id, $week_day, $id_zone, $booking_alive, $add_block) {
                foreach ($add_block as $block) {
                    $price = new Price();
                    $price->club_id = $club_id;
                    $price->name = $name;
                    $price->week_day = $week_day;
                    $price->id_zone = $id_zone;
                    $price->booking_alive = $booking_alive;
                    $price->time_start = $block->time_start;
                    $price->time_stop = $block->time_stop;
                    $price->price = $block->price;
                    $price->time_fixed = $block->time_fixed ?? null;
                    $price->duration = $block->duration ?? 0;
                    $price->tariff_type = ($block->duration == 0 && $block->time_fixed == null) ? 0 : 1;
                    try {
                        $price->save();
                    } catch (Exception) {
                        $error = true;
                    }
                }
                DB::commit();
            });
        } catch (Exception) {
            DB::rollBack();
        }
        return $error;
    }


    /**
     * @param mixed $club_id
     * @param mixed $type
     * @return \Illuminate\Support\Collection
     */
    public function getTariffByType(mixed $club_id, mixed $type): \Illuminate\Support\Collection
    {
        return DB::table('price')
            ->select('price', 'tariff_type', DB::raw("to_char(time_start, 'HH24:MI') as time_start"), DB::raw("to_char(time_stop, 'HH24:MI') as time_stop"), 'time_fixed', 'name')
            ->where([
                ['price.club_id', '=', $club_id],
                ['tariff_type', '=', $type],
            ])
            ->get();
    }
}
