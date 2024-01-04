<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientsNotes;
use App\Models\User;
use App\Models\UserAccess;
use App\Providers\UserCommon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected User $user;
    protected Client $client;
    protected ClientsNotes $clientsNotes;
    protected UserAccess $userAccess;

    /**
     * @param User $user
     * @param Client $client
     * @param ClientsNotes $clientsNotes
     * @param UserAccess $userAccess
     */
    public function __construct(User $user, Client $client, ClientsNotes $clientsNotes, UserAccess $userAccess)
    {
        $this->user = $user;
        $this->client = $client;
        $this->clientsNotes = $clientsNotes;
        $this->userAccess = $userAccess;
        $this->middleware('auth:api');
    }

    public function UsersFind(Request $request)
    {
        $searchUser = $request->user;
        $club_id = $request->club_id;
        return $this->user->UsersFind($searchUser, $club_id);
    }
    public function ageFilter(Request $request)
    {
        $ageString = $request->age;
        return $this->client->getAgeFilter($ageString);
    }
    public function getPermisions(Request $request) {
        $club_id = $request->club_id;
        $user_id = $request->user_id;
        return $this->userAccess->getByUserId($club_id, $user_id);
    }
    public function sendNewMind(Request $request)
    {
        $user_id = $request->user_id;
        $client_id = $request->client_id;
        $note = $request->note;

        $res = $this->clientsNotes->add($client_id, $user_id, $note);
        if($res) {
            return response()->json(['message' => 'Создано']);
        }else{
            return response()->json(['message' => 'Ошибка'], 400);
        }
    }
    public function getNotes(Request $request)
    {
        $user_id = $request->user_id;
        $client_id = $request->client_id;
        return $this->clientsNotes->getByUserAndClient($user_id, $client_id);
    }
}
