<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserGroupController extends Controller
{
    protected UserGroup$userGroup;

    /**
     * @param UserGroup $userGroup
     */
    public function __construct(UserGroup $userGroup)
    {
        $this->userGroup = $userGroup;
        $this->middleware('auth:api');
    }

    public function getGroup(Request $request): array
    {
        $club_id = $request->club_id;
        $userGroup = $this->userGroup->get($club_id);
        $outData=array();
        foreach ($userGroup as $group) {
            $inData = array();
            $inData['name'] = $group->name;
            $inData['discount'] = $group->discount;
            if($group->category1 === true && $group->category2 === true) {
                $inData['category'] = 'Все';
            }
            if($group->category1 === true && $group->category2 === false) {
                $inData['category'] = 'Товары';
            }
            if($group->category1 === false && $group->category2 === true) {
                $inData['category'] = 'Услуги';
            }
            if($group->category1 === false && $group->category2 === false) {
                $inData['category'] = 'Нет';
            }
            $inData['amount'] = $group->amount;
            if ($group->progress_return == 0) {
                $inData['progress_return'] = 'Нет';
            }
            else {
                $inData['progress_return'] = $group->progress_return;
            }
            $inData['id'] = $group->id;
            $outData[] = $inData;
        }
        return $outData;
    }
    public function addUserGroup(Request $request)
    {
        $name = $request->name;
        $discount = $request->discount;
        $category = $request->category;
        $amount = $request->amount;
        $progress_return = $request->progress_return;
        $club_id = $request->club_id;

        return $this->userGroup->addGroup($category, $name, $discount, $amount, $progress_return, $club_id);
    }
    public function editGroupSave(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $discount = $request->discount;
        $amount = $request->amount;
        $userGroup = $this->userGroup->getById($id);
        if ($userGroup != null) {
            $this->userGroup->add($name, $userGroup, $discount, $amount);
            return 'OK';
        }
        else
            return response('Error', 400);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        try {
            $this->userGroup->deleteGroup($id); // удаляем запись
            return response()->json(['message' => 'Запись успешно удалена'], 200); // возвращаем ответ об успешном удалении
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400); // возвращаем ответ об ошибке
        }
    }
}

