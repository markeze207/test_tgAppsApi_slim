<?php

namespace App\Models;

use App\core\Database;
use PDO;

class Pool
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

    public function getNew(): array
    {
        try {
            $oneMinuteAgo = time() - 120;
            $sql = "SELECT * FROM pool WHERE createTime BETWEEN ? AND ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$oneMinuteAgo, time()]);

            $pools = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($pools) {
                $poolsResult = $this->getPoolUser($pools);
                $data = ['result' => $poolsResult, 'status' => true];
            } else {
                $data = ['result' => 'Пулы не найдены', 'status' => false];
            }
        } catch (\Exception $exception) {
            $data = ['result' => $exception->getMessage(), 'status' => false];
        }

        return $data;
    }

    public function getPoolUser($pools): array
    {
        foreach($pools as $key => $pool)
        {
            $userModel = new User($pool['userId']);

            $user = $userModel->get()['result'];

            $pools[$key]['creatorName'] = $user['name'] ?? NULL;
            $pools[$key]['creatorAvatar'] = $user['avatar'] ?? NULL;
        }
        return $pools;
    }
}
