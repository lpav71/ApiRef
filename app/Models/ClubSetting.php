<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubSetting extends Model
{
    use HasFactory;

    protected $table = 'club_setting';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getDisignData(mixed $club_id)
    {
        return ClubSetting::where('club_id', $club_id)->first();
    }
}
