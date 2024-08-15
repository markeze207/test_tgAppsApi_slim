<?php

namespace App\Controllers;

use App\Models\Tasks;

class TasksController
{
    /**
     * @param $id
     * @return array
     */
    public function get($id): array
    {
        $tasksModel = new Tasks();
        return $tasksModel->get($id);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $tasksModel = new Tasks();
        return $tasksModel->getAll();
    }
}
