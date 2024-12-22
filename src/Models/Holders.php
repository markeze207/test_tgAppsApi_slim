<?php

namespace App\Models;

use App\core\Database;
use PDO;

class Holders
{
    private PDO $pdo;


    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getCountByPoolId($poolId): array
    {
        $holderPrepare = $this->pdo->prepare("SELECT COUNT(*) FROM holders WHERE poolId = ?");

        $holderPrepare->execute(array($poolId));

        $holdersCount = $holderPrepare->fetchColumn();

        if ($holdersCount) {
            return ['result' => $holdersCount, 'status' => true];
        } else {
            return ['result' => null, 'status' => false];
        }
    }

}
