<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDescription extends Model
{
    use HasFactory;
    protected $table = 'task_description';
    public $timestamps = false;

    /**
     * @param mixed $id
     * @return mixed
     */
    public function getTaskDescriptionById(mixed $id)
    {
        return TaskDescription::find($id);
    }
}
