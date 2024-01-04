<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;
    protected $table = 'users_setting';
    public $timestamps = false;

    /**
     * @param mixed $email
     * @param mixed $club_id
     * @param mixed $work_experience
     * @param mixed $rate
     * @param mixed $rate_percent
     * @return UserSetting
     */
    public function addUserSetting(mixed $email, mixed $club_id, mixed $work_experience, mixed $rate, mixed $rate_percent): UserSetting
    {
        $userSettings = new UserSetting();
        $userSettings->email = $email;
        $userSettings->club_id = $club_id;
        $userSettings->work_experience = $work_experience;
        $userSettings->rate = $rate;
        $userSettings->rate_percent = $rate_percent;
        $userSettings->save();
        return $userSettings;
    }
}
