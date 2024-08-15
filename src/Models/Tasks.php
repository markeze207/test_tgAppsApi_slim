<?php

namespace App\Models;

use App\core\Database;
use PDO;

class Tasks
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * @param $id
     * @return array
     */
    public function get($id): array
    {
        $task = $this->pdo->prepare("SELECT * FROM `tasks` WHERE `ID` = ?");
        $task->execute(array($id));
        $taskArray = $task->fetch(PDO::FETCH_ASSOC);

        if ($taskArray) {
            $data = ['result' => $taskArray, 'status' => true];
        } else {
            $data = ['result' => 'Задание не было найдено', 'status' => false];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $task = $this->pdo->prepare("SELECT * FROM `tasks`");
        $task->execute();
        $taskArray = $task->fetchAll(PDO::FETCH_ASSOC);

        if ($taskArray) {
            $data = ['result' => $taskArray, 'status' => true];
        } else {
            $data = ['result' => 'Задание не было найдено', 'status' => false];
        }

        return $data;
    }
}
