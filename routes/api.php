<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('registration', [\App\Http\Controllers\AuthController::class, 'registration']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);
    Route::post('me', [\App\Http\Controllers\AuthController::class, 'me']);
});

Route::post('saveMapPosition', [\App\Http\Controllers\api\MapController::class, 'saveMapPosition'])->name('saveMapPosition');
Route::post('getMapPosition', [\App\Http\Controllers\api\MapController::class, 'getMapPosition'])->name('getMapPosition');
Route::post('saveNewPosition', [\App\Http\Controllers\api\MapController::class, 'saveNewPosition'])->name('saveNewPosition');
Route::post('findusers', [App\Http\Controllers\api\UserController::class, 'UsersFind'])->name('UsersFind');
Route::post('age-filter', [App\Http\Controllers\api\UserController::class, 'ageFilter']);
Route::post('user/send-new-mind', [App\Http\Controllers\api\UserController::class, 'sendNewMind']);
Route::post('user/get-notes', [App\Http\Controllers\api\UserController::class, 'getNotes']);
Route::post('getSmena', [App\Http\Controllers\api\FinanceController::class, 'getSmena'])->name('getSmena');
Route::post('findopenshift', [App\Http\Controllers\api\FinanceController::class, 'findOpenShift'])->name('findOpenShift');
Route::post('getGames', [App\Http\Controllers\api\GameController::class, 'getGames'])->name('getGames');
Route::post('changeStatus', [App\Http\Controllers\api\GameController::class, 'changeStatus'])->name('changeStatus');
Route::post('searchGame', [App\Http\Controllers\api\GameController::class, 'searchGame'])->name('searchGame');
Route::post('finance/all',[\App\Http\Controllers\api\FinanceController::class,'getAllFinance']);
Route::post('finance/filter',[\App\Http\Controllers\api\FinanceController::class,'filterFinance']);
Route::post('finance/admins',[\App\Http\Controllers\api\FinanceController::class,'getAdmins']);
Route::post('finance/clients',[\App\Http\Controllers\api\FinanceController::class,'getClients']);
Route::post('shift/close',[\App\Http\Controllers\WebHooks\ShiftController::class, 'close'])->name('shift.close');
Route::post('shift/open',[\App\Http\Controllers\WebHooks\ShiftController::class, 'open'])->name('shift.open');
Route::post('verifyopenshift',[\App\Http\Controllers\api\FinanceController::class,'verifyOpenShift'])->name('verifyOpenShift');
Route::post('verifycloseshift',[\App\Http\Controllers\api\FinanceController::class,'verifyCloseShift'])->name('verifyCloseShift');
Route::post('games/getGameById', [App\Http\Controllers\api\GameController::class, 'getGameById'])->name('getGameById');
Route::post('games/savegame', [App\Http\Controllers\api\GameController::class, 'saveGame'])->name('saveGame');
Route::post('openOpenModal',[\App\Http\Controllers\WebHooks\ShiftController::class, 'openOpenModal'])->name('openOpenModal');
Route::post('openCloseModal',[\App\Http\Controllers\WebHooks\ShiftController::class, 'openCloseModal'])->name('openCloseModal');
Route::post('zone/getZone', [\App\Http\Controllers\api\ZoneController::class, 'getZone'])->name('getZone');
Route::post('map/findpc', [\App\Http\Controllers\api\MapController::class, 'findPCForAdd'])->name('findPCForAdd');
Route::post('sendMassage',[\App\Http\Controllers\api\FinanceController::class,'sendMassage'])->name('sendMassage');
Route::post('closepay',[\App\Http\Controllers\api\FinanceController::class,'closePaymentWindow'])->name('closePaymentWindow');
Route::post('shop/get', [\App\Http\Controllers\api\ShopController::class, 'getAll']);
Route::post('shop/find', [\App\Http\Controllers\api\ShopController::class, 'find']);
Route::post('client/all', [\App\Http\Controllers\api\ClientController::class, 'getClients']);
Route::post('shop/find/client', [\App\Http\Controllers\api\ShopController::class, 'searchClient']);
Route::post('booking/all', [\App\Http\Controllers\api\BookingController::class, 'getAllClients']);
Route::post('booking/cancel', [\App\Http\Controllers\api\BookingController::class, 'cancelBooking']);
Route::post('booking/get-all', [\App\Http\Controllers\api\BookingController::class, 'getAllBookingsPerDay']);
Route::post('booking/get-all-api', [\App\Http\Controllers\api\BookingController::class, 'getAllBookingsPerDayAPI']);
Route::post('booking/get-hours', [\App\Http\Controllers\api\BookingController::class, 'getAllBookingsHours']);
Route::post('zone/add', [\App\Http\Controllers\api\ZoneController::class, 'addZone']);
Route::post('promo/save', [\App\Http\Controllers\api\PromoController::class, 'save']);
Route::post('promo/get', [\App\Http\Controllers\api\PromoController::class, 'get']);
Route::post('tasks/tasks', [\App\Http\Controllers\api\TaskController::class, 'tasks']);
Route::post('tasks/get', [\App\Http\Controllers\api\TaskController::class, 'getTasks']);
Route::post('tasks/users', [\App\Http\Controllers\api\TaskController::class, 'users']);
Route::post('tasks/save', [\App\Http\Controllers\api\TaskController::class, 'saveAddModal']);
Route::post('tasks/save/edit', [\App\Http\Controllers\api\TaskController::class, 'saveEditModal']);
Route::post('tasks/new-status', [\App\Http\Controllers\api\TaskController::class, 'getNewStatus']);
Route::post('tasks/save-new-status', [\App\Http\Controllers\api\TaskController::class, 'saveNewStatus']);
Route::post('tasks/all', [\App\Http\Controllers\api\TaskController::class, 'allTasks']);
Route::post('booking/draw', [\App\Http\Controllers\api\BookingController::class, 'draw']);
Route::post('store/addedit', [\App\Http\Controllers\api\ShopController::class, 'addEdit']);
Route::post('price/get', [\App\Http\Controllers\api\TariffController::class, 'getTariff']);
Route::post('user/getpermissions', [\App\Http\Controllers\api\UserController::class, 'getPermisions']);
Route::post('position/getuser', [\App\Http\Controllers\api\PositionController::class, 'getUsers']);
Route::post('position/get', [\App\Http\Controllers\api\PositionController::class, 'getPositions']);
Route::post('licenses', [\App\Http\Controllers\api\LicensesController::class, 'licenses']);
Route::post('category/save', [\App\Http\Controllers\api\LicensesController::class, 'categorySave']);
Route::post('tariff/save', [\App\Http\Controllers\api\TariffController::class, 'save']);
Route::post('tariff/zone', [\App\Http\Controllers\api\TariffController::class, 'zone']);
Route::post('map/user-id', [\App\Http\Controllers\api\MapController::class, 'mapUserId']);
Route::post('reservations', [\App\Http\Controllers\api\BookingController::class, 'reservations']);
Route::post('user/cash', [\App\Http\Controllers\api\BookingController::class, 'userCash']);
Route::post('finance/data', [\App\Http\Controllers\api\FinanceController::class, 'financeModalData']);
Route::post('userinfo/shifts', [\App\Http\Controllers\api\UserInfoController::class, 'shifts']);
Route::post('games/addsteam', [App\Http\Controllers\api\GameController::class, 'addSteamAccount']);
Route::post('promo/codes', [\App\Http\Controllers\api\PromoController::class, 'codes']);
Route::post('club/setting/disign', [\App\Http\Controllers\api\ClubSettingController::class, 'disignData']);
Route::post('diagram/calc', [\App\Http\Controllers\api\FinanceController::class, 'diagramCalc']);
Route::post('user_group', [\App\Http\Controllers\api\UserGroupController::class, 'getGroup']);
Route::post('user_group/add', [\App\Http\Controllers\api\UserGroupController::class, 'addUserGroup']);
Route::post('user_group/edit', [\App\Http\Controllers\api\UserGroupController::class, 'editGroupSave']);
Route::post('user_group/delete', [\App\Http\Controllers\api\UserGroupController::class, 'delete']);
Route::post('message', [\App\Http\Controllers\api\SendToAdminController::class, 'getMessage']);
Route::post('deposit', [\App\Http\Controllers\api\HistoryDepositsController::class, 'getDeposits']);
Route::post('map/stopsos', [\App\Http\Controllers\api\MapController::class, 'stopSOS']);
Route::post('sales', [\App\Http\Controllers\api\HistorySalesController::class, 'getsales']);
Route::post('sale', [\App\Http\Controllers\api\HistorySalesController::class, 'sale']);
Route::post('statistic', [\App\Http\Controllers\api\DashboardController::class, 'statistic']);
Route::post('store-out/save', [\App\Http\Controllers\api\ShopController::class, 'storeOutSave']);
Route::post('client/getlogin', [\App\Http\Controllers\api\ClientController::class, 'getLogin']);
Route::post('booking/statistic', [\App\Http\Controllers\api\TariffStatisticController::class, 'statistic']);
Route::post('user/accesses', [\App\Http\Controllers\api\UserAccessController::class, 'accesses']);
Route::post('user/access/update', [\App\Http\Controllers\api\UserAccessController::class, 'update']);
Route::post('user/send_invitation', [\App\Http\Controllers\api\PositionController::class, 'SendInvitation']);
Route::post('user/registration', [\App\Http\Controllers\api\RegistrationController::class, 'registration']);
Route::post('user/verify', [\App\Http\Controllers\api\RegistrationController::class, 'verifyEmail']);
Route::post('client/add_amount', [\App\Http\Controllers\api\ClientController::class, 'addAmount']);
Route::post('cashbox_type', [\App\Http\Controllers\api\PayTerminalController::class, 'cashboxType']);
Route::post('cashbox_connect_type', [\App\Http\Controllers\api\PayTerminalController::class, 'cashboxConnectType']);
Route::post('cashbox/save', [\App\Http\Controllers\api\PayTerminalController::class, 'save']);
Route::post('cashbox/setting', [\App\Http\Controllers\api\PayTerminalController::class, 'setting']);
Route::post('sound/upload', [\App\Http\Controllers\api\SoundController::class, 'upload']);
Route::post('warehouse/add', [\App\Http\Controllers\api\ShopController::class, 'addToWarehouse']);
Route::post('warehouse/allreceipts', [\App\Http\Controllers\api\ReceiptController::class, 'allReceipts']);
Route::post('warehouse/filter', [\App\Http\Controllers\api\ReceiptController::class, 'filter']);
Route::post('promo/details', [\App\Http\Controllers\api\PromoController::class, 'details']);
Route::post('store/operation/type', [\App\Http\Controllers\api\ShopController::class, 'getStoreOparationType']);
Route::post('clients/purchases', [\App\Http\Controllers\api\CustomerPurchasesController::class, 'getPurchases']);
Route::post('clients/delivery', [\App\Http\Controllers\api\CustomerPurchasesController::class, 'delivery']);
Route::post('purchases/filter', [\App\Http\Controllers\api\CustomerPurchasesController::class, 'filter']);
Route::post('purchases/details', [\App\Http\Controllers\api\CustomerPurchasesController::class, 'details']);
Route::post('return/info', [\App\Http\Controllers\api\ReturnInfoController::class, 'info']);
Route::post('return/filter', [\App\Http\Controllers\api\ReturnInfoController::class, 'filter']);
Route::post('export', [\App\Http\Controllers\api\ExportController::class, 'export'])->name('export');
Route::post('export/clients', [\App\Http\Controllers\api\ExportController::class, 'exportClients'])->name('exportClients');
Route::post('import/clients', [\App\Http\Controllers\api\ExportController::class, 'importClients'])->name('importClients');
Route::post('task/get-new', [\App\Http\Controllers\api\TaskController::class, 'getNewTask']);
Route::post('task/unvisible', [\App\Http\Controllers\api\TaskController::class, 'unvisibleToast']);
Route::post('log/create', [\App\Http\Controllers\api\LoggerController::class, 'createLog']);


Route::post('test/all', [\App\Http\Controllers\api\TestController::class, 'all']);
Route::post('test/perday', [\App\Http\Controllers\api\TestController::class, 'perday']);
Route::post('test', [\App\Http\Controllers\api\TestController::class, 'test']);
