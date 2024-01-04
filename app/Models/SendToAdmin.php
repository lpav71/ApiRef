<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SendToAdmin extends Model
{
    use HasFactory;
    protected $table = 'send_to_admin';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return \Illuminate\Support\Collection
     */
    public function message(mixed $club_id): \Illuminate\Support\Collection
    {
        return DB::table('send_to_admin')->where('club_id', $club_id)->get();
    }
}
