<?php

namespace App\Repositories;

use App\Models\Task;
use App\Interfaces\TaskRepositoryInterface;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface {
    public function __construct(Task $task) {
        parent::__construct($task);
    }

    // You can add additional methods specific to the Task model here
}
