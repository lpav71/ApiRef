<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreOperationType extends Model
{
    use HasFactory;
    protected $table = 'store_operation_type';
    public $timestamps = false;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): \Illuminate\Support\Collection
    {
        return DB::table('store_operation_type')->get();
    }
}
