<?php

namespace App\Controllers;

use App\Models\Validate;

class ValidateController
{
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
