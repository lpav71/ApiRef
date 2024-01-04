<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ClientController extends Controller
{
    protected Client $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getClients(Request $request): Collection
    {
        $club_id = $request->club_id;
        $clientData = $this->client->clients($club_id);
        return $clientData;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getLogin(Request $request)
    {
        $user_id = $request->user_id;
        return $this->client->login($user_id);
    }

    public function addAmount(Request $request)
    {
        $summa = $request->summa;
        $client_id = $request->client_id;
        $this->client->addAmount($client_id,$summa);
    }
}
