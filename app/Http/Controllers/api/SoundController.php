<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SoundController extends Controller
{
    public function upload(Request $request)
    {
        $club_id = $request->club_id;
        $club = Club::find($club_id);

        if ($club != null)
        {
            for ($i = 1; $i<=9; $i++) {
                $key = 'sound' . $i;
                if ($request->hasFile($key) && $request->file($key)->isValid()) {
                    $sounds[$key] = $request->file($key)->store('public/sounds');
                    $oldSound = 'sound' . $i;
                    if ($club->{$oldSound} != null)
                        Storage::delete($club->{$oldSound});
                }
            }

            foreach ($sounds as $key => $sound) {
                $club->{$key} = $sound;
            }
            $club->save();
            return array('status' => 'OK');
        }
    }
}
