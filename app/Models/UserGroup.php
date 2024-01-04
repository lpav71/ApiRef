<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    protected $table = 'user_group';
    public $timestamps = false;

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function get(int $club_id)
    {
        return UserGroup::where('club_id', $club_id)->get();
    }

    /**
     * @param mixed $category
     * @param mixed $name
     * @param mixed $discount
     * @param mixed $amount
     * @param mixed $progress_return
     * @param mixed $club_id
     * @return UserGroup
     */
    public function addGroup(mixed $category, mixed $name, mixed $discount, mixed $amount, mixed $progress_return, mixed $club_id): UserGroup
    {
        switch ($category) {
            case 'all':
                $category1 = true;
                $category2 = true;
                break;
            case 'goods':
                $category1 = true;
                $category2 = false;
                break;
            case 'services':
                $category1 = false;
                $category2 = true;
                break;
            case 'not':
                $category1 = false;
                $category2 = false;
                break;
        }

        $userGroup = new UserGroup();
        $userGroup->name = $name;
        $userGroup->discount = $discount;
        $userGroup->category1 = $category1;
        $userGroup->category2 = $category2;
        $userGroup->amount = $amount;
        $userGroup->progress_return = $progress_return;
        $userGroup->club_id = $club_id;
        $userGroup->save();
        return $userGroup;
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function getById(mixed $id)
    {
        return UserGroup::where('id', $id)->first();
    }

    /**
     * @param mixed $name
     * @param mixed $userGroup
     * @param mixed $discount
     * @param mixed $amount
     * @return void
     */
    public function add(mixed $name, mixed $userGroup, mixed $discount, mixed $amount): void
    {
        $userGroup->name = $name;
        $userGroup->discount = $discount;
        $userGroup->amount = $amount;
        $userGroup->save();
    }

    /**
     * @param mixed $id
     * @return void
     */
    public function deleteGroup(mixed $id): void
    {
        UserGroup::destroy($id);
    }
}
