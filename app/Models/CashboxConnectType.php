<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashboxConnectType extends Model
{
    use HasFactory;
    protected $table = 'cashbox_connect_type';
    public $timestamps = false;



    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCashboxConnectYpeAll(): \Illuminate\Support\Collection
    {
        return DB::table('cashbox_connect_type')->get();
    }
}
