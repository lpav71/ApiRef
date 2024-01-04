<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    protected User $userModel;

    /**
     * @param User $userModel
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
        $this->middleware('auth:api');
    }

    public function registration (Request $request): array
    {
        $club_id = $request->club_id;
        $idUserSetting = $request->id_user_setting;
        $email = $request->email;
        $phone = $request->phone;
        $login = $request->login;
        $lastName = $request->lastName;
        $firstName = $request->firstName;
        $middleName = $request->middleName;
        $birthDate = $request->birthDate;
        $pass1 = $request->pass1;
        $passwordHash = bcrypt($pass1);

        $this->userModel->userAdd($email, $club_id, $login, $phone, $lastName, $firstName, $middleName, $passwordHash, $birthDate, $idUserSetting);
        return array('status' => 'OK');
    }

    public function verifyEmail(Request $request): array|JsonResponse
    {
        $email = $request->email;
        return $this->userModel->emailVerify($email);
    }
}
