<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $table = 'position';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getAllPositions(mixed $club_id)
    {
        return Position::where('club_id', $club_id)->get();
    }
}
