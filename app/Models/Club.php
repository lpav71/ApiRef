<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Club extends Model
{
    use HasFactory;

    protected $table = 'club';
    public $timestamps = false;

    /**
     * @param Request $request
     * @return mixed
     */
    public function getClubByApiKey(string $api_key)
    {
        return Club::where('api_key', $api_key)->first();
    }
}
