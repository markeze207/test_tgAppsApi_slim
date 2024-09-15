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
        $data = $this->validate($_ENV['BOT_TOKEN'], $initData);

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
    public function validate($bot_token, $initData): array
    {
        if (Validate::isSafe($bot_token, $initData)) {
            $validateClass = new Validate();
            $query = $validateClass->parseQuery($initData);

            $user = json_decode($query['user'], true);

            $jwt = $validateClass->generateToken($user['id']);

            return ['jwt' => $jwt, 'user' => $user, 'status' => true];
        } else {
            return ['status' => false];
        }
    }
}
