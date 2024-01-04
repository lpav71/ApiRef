<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PositionController extends Controller
{
    protected UserSetting $userSetting;
    protected User $user;
    protected Position $position;

    /**
     * @param UserSetting $userSetting
     * @param User $user
     * @param Position $position
     */
    public function __construct(UserSetting $userSetting, User $user, Position $position)
    {
        $this->userSetting = $userSetting;
        $this->user = $user;
        $this->position = $position;
        $this->middleware('auth:api');
    }


    public function getPermisions(Request $request)
    {
        return $this->user->getPermissions($request->user_id, $request->club_id);
    }

    public function getUsers(Request $request)
    {
        $club_id = $request->club_id;
        return $this->user->getUserAndPosition($club_id);
    }
    public function getPositions(Request $request)
    {
        $club_id = $request->club_id;
        return $this->position->getAllPositions($club_id);
    }
    public function SendInvitation(Request $request)
    {
        $club_id = $request->club_id;
        $email = $request->email;
        $rate = $request->rate;
        $rate_percent = $request->rate_percent;
        $work_experience = $request->work_experience;

        $userSettings = $this->userSetting->addUserSetting($email, $club_id, $work_experience, $rate, $rate_percent);
        $idUserSetting = $userSettings->id;
        $url = URL::temporarySignedRoute('registration', now()->addMinutes(60), ['idUserSetting' => $idUserSetting, 'email' => $email]);

        $body = array(
            "cmd" => "sendmail",
            "email" => $email,
            "link" => $url
        );
        $bobyJson = json_encode($body);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://45.135.165.89:8082',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$bobyJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return array (
            'status' => 'OK'
        );
    }
}
