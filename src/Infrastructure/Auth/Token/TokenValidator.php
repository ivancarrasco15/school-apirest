<?php

namespace App\Infrastructure\Auth\Token;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenValidator {
    private string $secret;

    public function __construct() {
        $this->secret = $_ENV['JWT_SECRET'];
    }

    public function validate(string $token): ?object {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Throwable) {
            return null;
        }
    }
}
