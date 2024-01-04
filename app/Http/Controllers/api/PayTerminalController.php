<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CashboxConnectType;
use App\Models\CashboxSetting;
use App\Models\CashboxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayTerminalController extends Controller
{
    protected CashboxSetting $cashboxSetting;
    protected CashboxType $cashboxType;
    protected CashboxConnectType $cashboxConnectType;

    /**
     * @param CashboxSetting $cashboxSetting
     * @param CashboxType $cashboxType
     * @param CashboxConnectType $cashboxConnectType
     */
    public function __construct(CashboxSetting $cashboxSetting, CashboxType $cashboxType, CashboxConnectType $cashboxConnectType)
    {
        $this->cashboxSetting = $cashboxSetting;
        $this->cashboxType = $cashboxType;
        $this->cashboxConnectType = $cashboxConnectType;
        $this->middleware('auth:api');
    }

    public function cashboxType()
    {
        return $this->cashboxType->getCashboxTypeAll();
    }

    public function cashboxConnectType()
    {
        return $this->cashboxConnectType->getCashboxConnectYpeAll();
    }

    public function save(Request $request)
    {
        $club_id = $request->club_id;
        $cashbox_type = $request->cashbox_type;
        $cashbox_connect_type = $request->cashbox_connect_type;
        $terminal_type = $request->terminal_type;
        $terminal_soft = $request->terminal_soft;
        $cashbox_connect_address = $request->cashbox_connect_address;

        $cashboxSetting = $this->cashboxSetting->CashboxSettingsAddOrUpdate($club_id, $cashbox_type, $cashbox_connect_type, $terminal_type, $terminal_soft, $cashbox_connect_address);
        return $cashboxSetting->id;
    }

    public function setting(Request $request)
    {
        $club_id = $request->club_id;
        return $this->cashboxSetting->getFirstCashBoxSettings($club_id);
    }
}
