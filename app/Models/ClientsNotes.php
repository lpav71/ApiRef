<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientsNotes extends Model
{
    use HasFactory;

    protected $table = 'clients_notes';
    public $timestamps = false;

    /**
     * @param mixed $client_id
     * @param mixed $user_id
     * @param mixed $note
     * @return bool
     */
    public function add(mixed $client_id, mixed $user_id, mixed $note): bool
    {
        $clientNotes = new ClientsNotes();
        $clientNotes->client_id = $client_id;
        $clientNotes->user_id = $user_id;
        $clientNotes->note = $note;
        $res = $clientNotes->save();
        return $res;
    }

    /**
     * @param mixed $user_id
     * @param mixed $client_id
     * @return mixed
     */
    public function getByUserAndClient(mixed $user_id, mixed $client_id)
    {
        return ClientsNotes::where('user_id', $user_id)->where('client_id', $client_id)->orderBy('created_at', 'desc')->get();
    }
}
