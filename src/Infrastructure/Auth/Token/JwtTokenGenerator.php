<?php

namespace App\Infrastructure\Auth\Token;

use Firebase\JWT\JWT;

class JwtTokenGenerator {
    private string $secret;

    public function __construct() {
        $this->secret = $_ENV['JWT_SECRET'];
    }

    public function generate(array $userData): string {
        $payload = [
            'iss'   => 'school-api',
            'iat'   => time(),
            'exp'   => time() + 3600,
            'sub'   => $userData['id'],
            'name'  => $userData['name'],
            'email' => $userData['email'],
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }
}
