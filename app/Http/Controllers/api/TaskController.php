<?php

namespace App\Http\Controllers\api;

use App\Http\Common\TaskCommon;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskDescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    protected Task $task;
    protected User $user;
    protected TaskDescription $taskDescription;

    /**
     * @param Task $task
     * @param User $user
     * @param TaskDescription $taskDescription
     */
    public function __construct(Task $task, User $user, TaskDescription $taskDescription)
    {
        $this->task = $task;
        $this->user = $user;
        $this->taskDescription = $taskDescription;
        $this->middleware('auth:api');
    }


    public function tasks(Request $request)
    {
        $club_id = $request->club_id;
        $admin_id = $request->admin_id;

        $tasks = $this->task->getAllTasks($club_id, $admin_id);
        for ($i = 0; $i <= count($tasks)-1; $i++) {
            $tasks[$i]->create_dt = Carbon::parse($tasks[$i]->create_dt)->format('d.m.Y H:i');
        }
        return $tasks;
    }
    public function users(Request $request)
    {
        $club_id = $request->club_id;
        return $this->user->getUserByClubId($club_id);
    }
    public function saveAddModal(Request $request)
    {
        $descript_admin = $request->descript_admin;
        $date_public = $request->date_public;
        $days = $request->days;
        $hours = $request->hours;
        $executor = $request->executor;
        $club_id = $request->club_id;
        $admin_id = $request->admin_id;

        $end_dt = Carbon::parse($date_public)->addDay($days)->addHours($hours);
        $task = $this->task->addTask($descript_admin, $date_public, $end_dt, $executor, $admin_id, $club_id);
        return $task;
    }
    public function saveEditModal(Request $request)
    {
        $id_task = $request->id_task;
        $descript_admin = $request->descript_admin;
        $date_public = $request->date_public;
        $days = $request->days;
        $hours = $request->hours;
        $executor = $request->executor;
        $club_id = $request->club_id;
        $admin_id = $request->admin_id;

        $end_dt = Carbon::parse($date_public)->addDay($days)->addHours($hours);
        $task = $this->task->updateTask($id_task, $descript_admin, $date_public, $end_dt, $executor, $admin_id, $club_id);
        return $task;
    }
    public function getNewStatus(Request $request)
    {
        $id = $request->id;
        return $this->taskDescription->getTaskDescriptionById($id);
    }
    public function saveNewStatus(Request $request)
    {
        $id = $request->id;
        $idDesk = $request->id_desk;
        $task = $this->task->updateStatus($id, $idDesk);
        return $task;
    }
    public function allTasks(Request $request)
    {
        $tasks = $this->task->getAll($request->club_id, $request->admin_id);
        return $tasks;
    }
    public function getNewTask(Request $request)
    {
        $club_id = $request->club_id;
        $user_id = $request->user_id;

        $count = $this->task->getCount($club_id, $user_id);
        return $count;
    }
    public function unvisibleToast(Request $request)
    {
        $c = $this->task->offToast($request);
        return $c;
    }
    public function getTasks(Request $request)
    {
        $club_id = $request->club_id;
        $admin_id = $request->admin_id;
        $tasks = $this->task->getAll($club_id, $admin_id);
        return $tasks;
    }
}
