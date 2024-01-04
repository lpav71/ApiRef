<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Task extends Model
{
    use HasFactory;
    protected $table = 'task';
    public $timestamps = false;

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllTasks(int $club_id, int $admin_id)
    {
        return Task::where('club_id', $club_id)
            ->select('task.id', 'club_id', 'admin_id', 'create_dt', 'employ', 'descript_admin', 'color')
            ->join('task_description', 'task_description.id', 'status')
            ->where('admin_id', $admin_id)
            ->whereIn('status', [1, 2, 3])
            ->orderBy('create_dt')
            ->get();
    }

    public function getAll($club_id, $admin_id)
    {
        $tasks = Task::where('task.admin_id', $admin_id)
            ->where('task.club_id', $club_id)
            ->join('task_description','task.status','task_description.id')
            ->leftJoin('users as users', 'task.admin_id', 'users.id')
            ->leftJoin('users as employ', 'task.employ', 'employ.id')
            ->select('task.descript_admin', 'task.id', 'task.create_dt', 'task.end_dt','users.name','employ.name as employ','task.employ as executor_id','users.name','task_description.description', 'task_description.color', 'task_description.id as id_desk')
            ->orderBy('create_dt')
            ->get();
        return $tasks;
    }

    /**
     * @param mixed $descript_admin
     * @param mixed $date_public
     * @param Carbon $end_dt
     * @param mixed $executor
     * @param mixed $admin_id
     * @param mixed $club_id
     * @return Task
     */
    public function addTask(mixed $descript_admin, mixed $date_public, Carbon $end_dt, mixed $executor, mixed $admin_id, mixed $club_id): Task
    {
        $task = new Task();
        $task->descript_admin = $descript_admin;
        $task->create_dt = $date_public;
        $task->end_dt = $end_dt;
        $task->employ = $executor;
        $task->admin_id = $admin_id;
        $task->club_id = $club_id;
        $task->status = 1;
        $task->save();
        return $task;
    }

    /**
     * @param mixed $id_task
     * @param mixed $descript_admin
     * @param mixed $date_public
     * @param Carbon $end_dt
     * @param mixed $executor
     * @param mixed $admin_id
     * @param mixed $club_id
     * @return mixed
     */
    public function updateTask(mixed $id_task, mixed $descript_admin, mixed $date_public, Carbon $end_dt, mixed $executor, mixed $admin_id, mixed $club_id)
    {
        $task = Task::where('id', $id_task)->first();
        $task->descript_admin = $descript_admin;
        $task->create_dt = $date_public;
        $task->end_dt = $end_dt;
        $task->employ = $executor;
        $task->admin_id = $admin_id;
        $task->club_id = $club_id;
        $task->status = 1;
        $task->save();
        return $task;
    }

    /**
     * @param mixed $id
     * @param mixed $idDesk
     * @return mixed
     */
    public function updateStatus(mixed $id, mixed $idDesk)
    {
        $task = Task::find($id);
        $task->status = $idDesk;
        $task->save();
        return $task;
    }

    /**
     * @param mixed $club_id
     * @param mixed $user_id
     * @return mixed
     */
    public function getCount(mixed $club_id, mixed $user_id)
    {
        return Task::where('club_id', $club_id)
            ->where('visible', true)
            ->where('admin_id', $user_id)
            ->count();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function offToast(Request $request)
    {
        return Task::where('club_id', $request->club_id)->where('admin_id', $request->user_id)->update(['visible' => false]);
    }
}
