<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cashbox;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected Cashbox $cashbox;

    /**
     * @param Cashbox $cashbox
     */
    public function __construct(Cashbox $cashbox)
    {
        $this->cashbox = $cashbox;
        $this->middleware('auth:api');
    }

    public function statistic(Request $request)
    {
        $admin = $request->admin;
        $types1 = [1, 2, 7, 8];
        $types2 = [1, 2];
        $types3 = [7, 8];
        $TotalRevenue = $this->cashbox->getRevenue($admin, $types1);

        $IncomeFromPC = $this->cashbox->getRevenue($admin, $types2);

        $IncomeFromGoods = $this->cashbox->getRevenue($admin, $types3);

        $AverageRevenueAdministrator = $this->cashbox->getAverageRevenue($admin, $types1);

        $outData = array(
            'TotalRevenue' =>$TotalRevenue->sum,
            'IncomeFromPC' => $IncomeFromPC->sum,
            'IncomeFromGoods' => $IncomeFromGoods->sum,
            'RevenueFromConsoles' => 0,
            'AverageRevenueAdministrator' => $AverageRevenueAdministrator->sum
        );

        return $outData;
    }
}
