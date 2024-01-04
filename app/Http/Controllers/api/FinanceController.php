<?php

namespace App\Http\Controllers\api;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use App\Models\Cashbox;
use App\Models\Client;
use App\Models\Club;
use App\Models\Finance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use stdClass;

class FinanceController extends Controller
{
    protected Finance $finance;
    protected User $user;
    protected Client $client;
    protected Club $club;
    protected Cashbox $cashbox;

    /**
     * @param Finance $finance
     * @param User $user
     * @param Client $client
     * @param Club $club
     * @param Cashbox $cashbox
     */
    public function __construct(Finance $finance, User $user, Client $client, Club $club, Cashbox $cashbox)
    {
        $this->finance = $finance;
        $this->user = $user;
        $this->client = $client;
        $this->club = $club;
        $this->cashbox = $cashbox;
        $this->middleware('auth:api');
    }

    public function getSmena(Request $request): array
    {
        $clubId = $request->club_id;
        $finance = $this->finance->getSmenaFinanceTrue($clubId);
        $shift = 'open';
        if (count($finance) == 0) {
            $finance = $this->finance->getSmenaFinanceFalse($clubId);
            $shift = 'close';
        }
        $outData = array(
            'shift' => $shift,
            'finance' => $finance
        );
        return $outData;
    }

    public function diagramCalc(Request $request): ?stdClass
    {
        $clubId = $request->club_id;
        return $this->finance->getDiagramCalc($clubId);
    }

    public function findOpenShift(Request $request): array
    {
        $club_id = $request->club_id;
        $finance = $this->finance->getFinanceStatusTrue($club_id);
        $count = count($finance);
        if ($count > 0) {
            $outData = array(
                'shiftStatus' => 'open'
            );
        } else {
            $outData = array(
                'shiftStatus' => 'close'
            );
        }
        return $outData;
    }

    public function getAdmins(Request $request): Collection
    {
        $club_id = $request->club_id;
        return $this->user->admins($club_id);
    }

    public function getClients(Request $request): Collection
    {
        $club_id = $request->club_id;
        return $this->client->clients($club_id);
    }

    public function getAllFinance(Request $request): Collection
    {
        $club_id = $request->club_id;
        $finance = $this->finance->allFinance($club_id);
        foreach ($finance as $fin) {
            if ($fin->status) {
                $fin->status = 'Смена открыта';
            } else {
                $fin->status = 'Смена закрыта';
            }
        }
        return $finance;
    }

    public function filterFinance(Request $request): Collection
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $admin_id = $request->admin_id;
        $club_id = $request->club_id;
        $finance = $this->finance->getFilterFinance($club_id, $admin_id, $start_date, $end_date);
        foreach ($finance as $fin) {
            if ($fin->status) {
                $fin->status = 'Смена открыта';
            } else {
                $fin->status = 'Смена закрыта';
            }
        }
        return $finance;
    }

    public function sendMassage(): void
    {
        event(new MyEvent('hello world'));
    }

    public function closePaymentWindow(Request $request)
    {
        $api_key = $request->api_key;
        $club = $this->club->getClubByApiKey($api_key);
        if ($club !== null) {
            $data = array(
                'id' => $club->id,
                'operation' => 'close'
            );
            event(new MyEvent($data));
        }
    }

    public function financeModalData(Request $request): Collection
    {
        $club_id = $request->club_id;
        $shift_num = $request->shift_num;
        return $this->cashbox->getCachboxForFinanceModal($club_id, $shift_num);
    }
}
