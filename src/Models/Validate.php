<?php

namespace App\Models;

use Firebase\JWT\JWT;
use function strlen;

class Validate
{
    public static function isSafe(string $botToken, string $initData): bool
    {
        [$checksum, $sortedInitData] = self::convertInitData($initData);
        $secretKey                   = hash_hmac('sha256', $botToken, 'WebAppData', true);
        $hash                        = bin2hex(hash_hmac('sha256', $sortedInitData, $secretKey, true));

        return 0 === strcmp($hash, $checksum);
    }

    private static function convertInitData(string $initData): array
    {
        $initDataArray = explode('&', rawurldecode($initData));
        $needle        = 'hash=';
        $hash          = '';

        foreach ($initDataArray as &$data) {
            if (substr($data, 0, strlen($needle)) === $needle) {
                $hash = substr_replace($data, '', 0, strlen($needle));
                $data = null;
            }
        }
        $initDataArray = array_filter($initDataArray);
        sort($initDataArray);

        return [$hash, implode("\n", $initDataArray)];
    }

    public function parseQuery($queryString): array
    {
        $params = [];
        parse_str($queryString, $params);

        return $params;
    }

    /**
     * @param $userId
     * @return string
     */
    public function generateToken($userId): string
    {
        $payload = [
            'iss' => 'api',  // Издатель токена
            'sub' => $userId,        // Идентификатор пользователя
            'iat' => time(),           // Время выдачи токена
            'exp' => time() + (60 * 60 * 24), // Время истечения токена (24 часа)
        ];

        $secretKey = $_ENV['SECRET_KEY'];

        return JWT::encode($payload, $secretKey, 'HS256');
    }
}
