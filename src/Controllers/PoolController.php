<?php

namespace App\Controllers;

use App\Models\Pool;

class PoolController
{
    /**
     * @return array
     */
    public function getNew(): array
    {
        $poolModel = new Pool();
        return $poolModel->getNew();
    }

    public function getTop()
    {
        $poolModel = new Pool();
        return $poolModel->getTop();
    }
}
