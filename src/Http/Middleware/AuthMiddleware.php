<?php

namespace App\Http\Middleware;

use App\Http\Responsejson;
use App\Infrastructure\Auth\Token\TokenValidator;

class AuthMiddleware {
    public function handle(): bool {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!str_starts_with($header, 'Bearer ')) {
            (new Responsejson(401, ['msg' => 'Unauthorized']))->send();
            return false;
        }

        $token = substr($header, 7);
        $payload = (new TokenValidator())->validate($token);

        if (!$payload) {
            (new Responsejson(401, ['msg' => 'Invalid or expired token']))->send();
            return false;
        }

        return true;
    }
}
