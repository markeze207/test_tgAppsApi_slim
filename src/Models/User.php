<?php

namespace App\Models;

use App\core\Database;
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
     * @param $name
     * @return array
     */
    public function create($name): array
    {
        $users = $this->pdo->prepare("INSERT INTO `users` (`ID`, `name`) VALUES (?, ?)");

        $users->execute(array($this->id, htmlspecialchars($name)));

        if ($users->rowCount() > 0) {
            return ['result' => 'Пользователь успешно создан', 'status' => true];
        } else {
            return ['result' => 'Ошибка при создании пользователя', 'status' => false];
        }
    }
}
