<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;
    public $timestamps = false;

    /**
     * @param Request $request
     * @return Collection
     */
    public function clients(int $club_id): Collection
    {
        return DB::table('clients')
            ->where('club_id', '=', $club_id)
            ->get();
    }

    public function clients2(int $club_id): Collection
    {
        return Client::where('club_id', $club_id)->get();
    }
    /**
     * @param mixed $user_id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function login(mixed $user_id)
    {
        return DB::table('clients')
            ->where('id', $user_id)
            ->first('login');
    }

    /**
     * @param int $id
     * @return mixed
     */
    function getClientById(int $id)
    {
        return Client::where('id', $id)->first();
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function getAllClientsByIds(array $ids)
    {
        return Client::whereIn('id', $ids)->get();
    }
    /**
     * @param int $userId
     * @param float $summa
     * @return void
     */
    public function addAmount(int $client_id, float $summa)
    {
        $client = $this->find($client_id);
        $client->amount += $summa;
        $client->save();
    }

    /**
     * @param mixed $login
     * @return mixed
     */
    public function getByLogin(mixed $login)
    {
        return Client::where('login', $login)->first();
    }

    /**
     * @param mixed $ageString
     * @return mixed
     */
    public function getAgeFilter(mixed $ageString)
    {
        switch ($ageString) {
            case "0-12":
                $clients = Client::whereRaw("age(bday) < '12 years'")->get();
                break;
            case "12-16":
                $clients = Client::whereRaw("age(bday) BETWEEN '12 years' AND '16 years'")->get();
                break;
            case "16-18":
                $clients = Client::whereRaw("age(bday) BETWEEN '16 years' AND '18 years'")->get();
                break;
            case "18-24":
                $clients = Client::whereRaw("age(bday) BETWEEN '18 years' AND '24 years'")->get();
                break;
            case "25-29":
                $clients = Client::whereRaw("age(bday) BETWEEN '25 years' AND '29 years'")->get();
                break;
            case "30-999":
                $clients = Client::whereRaw("age(bday) > '30 years'")->get();
                break;
        }
        return $clients;
    }
}
