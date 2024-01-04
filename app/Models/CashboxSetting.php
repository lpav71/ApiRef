<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashboxSetting extends Model
{
    use HasFactory;
    protected $table = 'cashbox_setting';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @param mixed $cashbox_type
     * @param mixed $cashbox_connect_type
     * @param mixed $terminal_type
     * @param mixed $terminal_soft
     * @param mixed $cashbox_connect_address
     * @return CashboxSetting
     */
    public function CashboxSettingsAddOrUpdate(mixed $club_id, mixed $cashbox_type, mixed $cashbox_connect_type, mixed $terminal_type, mixed $terminal_soft, mixed $cashbox_connect_address): CashboxSetting
    {
        $cashboxSetting = CashboxSetting::where('club_id', $club_id)->first();
        if ($cashboxSetting == null) {
            $cashboxSetting = new CashboxSetting();
            $cashboxSetting->club_id = $club_id;
            $cashboxSetting->type = $cashbox_type;
            $cashboxSetting->connect_type = $cashbox_connect_type;
            $cashboxSetting->terminal_type = $terminal_type;
            $cashboxSetting->terminal_soft = $terminal_soft;
            $cashboxSetting->connect_address = $cashbox_connect_address;
            $cashboxSetting->save();
        } else {
            $cashboxSetting->type = $cashbox_type;
            $cashboxSetting->connect_type = $cashbox_connect_type;
            $cashboxSetting->terminal_type = $terminal_type;
            $cashboxSetting->terminal_soft = $terminal_soft;
            $cashboxSetting->connect_address = $cashbox_connect_address;
            $cashboxSetting->save();
        }
        return $cashboxSetting;
    }

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getFirstCashBoxSettings(mixed $club_id)
    {
        return CashboxSetting::where('club_id', $club_id)->first();
    }
}
