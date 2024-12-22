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
            $sql = "SELECT p.*, u.name AS creatorName, u.avatar AS creatorAvatar,
                   (SELECT COUNT(*) FROM holders h WHERE h.poolId = p.ID) AS holdersCount
            FROM pool p
            LEFT JOIN users u ON p.userId = u.id
            ORDER BY p.createTime ASC
            LIMIT 50";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $pools = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($pools) {
                $data = ['result' => $pools, 'status' => true];
            } else {
                $data = ['result' => 'Пулы не найдены', 'status' => false];
            }
        } catch (\Exception $exception) {
            $data = ['result' => $exception->getMessage(), 'status' => false];
        }

        return $data;
    }

    public function getTop(): array
    {
        try {
            $sql = "SELECT p.*, 
                   u.name AS creatorName, 
                   u.avatar AS creatorAvatar, 
                   COUNT(h.poolId) AS holdersCount
            FROM pool p
            LEFT JOIN holders h ON p.id = h.poolId
            LEFT JOIN users u ON p.userId = u.id
            GROUP BY p.id
            ORDER BY holdersCount DESC
            LIMIT 50";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $pools = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($pools) {
                $data = ['result' => $pools, 'status' => true];
            } else {
                $data = ['result' => 'Пулы не найдены', 'status' => false];
            }
        } catch (\Exception $exception) {
            $data = ['result' => $exception->getMessage(), 'status' => false];
        }

        return $data;
    }

}
