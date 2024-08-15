<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    private int $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $userModel = new User($this->id);
        return $userModel->get();
    }

    /**
     * @return array
     */
    public function getTasks(): array
    {
        $userModel = new User($this->id);

        return $userModel->getTasks();
    }

    /**
     * @param $name
     * @return array
     */
    public function create($name): array
    {
        $userModel = new User($this->id);
        return $userModel->create($name);
    }
}