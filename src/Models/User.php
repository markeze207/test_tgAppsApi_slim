<?php

namespace App\Models;

use App\core\Database;
use Exception;
use PDO;

class User
{
    private PDO $pdo;

    private int $id;

    public function __construct($id)
    {
        $this->pdo = Database::getConnection();

        $this->id = $id;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $userData = $this->pdo->prepare("SELECT * FROM users WHERE ID = ?");

        $userData->execute(array($this->id));

        $user = $userData->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return ['result' => $user, 'status' => true];
        } else {
            return ['result' => 'Пользователь не найден', 'status' => false];
        }
    }

    /**
     * @return array
     */
    public function getTasks(): array
    {

        $tasksData = $this->pdo->prepare("SELECT * FROM tasks");

        $tasksData->execute();

        $tasksArray = $tasksData->fetchAll(PDO::FETCH_ASSOC);

        if ($tasksArray) {
            foreach ($tasksArray as $key => $task) {
                $userData = $this->pdo->prepare("SELECT `task_id` FROM tasks_users WHERE user_id = ? AND task_id = ?");

                $userData->execute(array($this->id, $task['ID']));

                $userTasks = $userData->fetch(PDO::FETCH_ASSOC);

                if ($userTasks) {
                    $tasksArray[$key]['competed'] = true;
                } else {
                    $tasksArray[$key]['competed'] = false;
                }
            }
            return ['result' => $tasksArray, 'status' => true];
        } else {
            return ['result' => 'Задания не найдены', 'status' => false];
        }
    }

    /**
     * @param $name
     * @return array
     */
    public function create($name): array
    {
        try {
            $users = $this->pdo->prepare("INSERT INTO `users` (`ID`, `name`) VALUES (?, ?)");

            $users->execute(array($this->id, $name));
            return ['result' => 'Пользователь успешно создан','status' => true];
        } catch (Exception $e) {
            return ['result' => 'Произошла ошибка '.$e->getMessage(),'status' => false];
        }
    }
}
