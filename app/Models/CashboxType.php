<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashboxType extends Model
{
    use HasFactory;

    protected $table = 'cashbox_type';
    public $timestamps = false;


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCashboxTypeAll(): \Illuminate\Support\Collection
    {
        return DB::table('cashbox_type')->get();
    }
}
