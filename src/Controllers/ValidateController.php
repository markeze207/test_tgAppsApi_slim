<?php

namespace App\Controllers;

use App\Models\Validate;

class ValidateController
{
    public function startValidate($data): array
    {
        if (empty($data['initData'])) {
            return ['result' => 'Некорректные данные', 'status' => false];
        }

        $initData = $data['initData'];
        $validateClass = new Validate();
        $data = $validateClass->validate($_ENV['BOT_TOKEN'], $initData);

        if (!$data['status']) {
            return ['result' => 'Произошла ошибка генерации', 'status' => false];
        }

        return $this->handleUserValidation($data);
    }

    private function handleUserValidation($data): array
    {
        $userController = new UserController($data['user']['id']);
        $user = $userController->get();

        if ($user['status']) {
            return ['result' => $data['jwt'], 'status' => true];
        }

        $userCreate = $userController->create($data['user']['username']);
        if ($userCreate['status']) {
            return ['result' => $data['jwt'], 'status' => true];
        }

        return ['result' => 'Произошла ошибка создания пользователя', 'status' => false];
    }
}
