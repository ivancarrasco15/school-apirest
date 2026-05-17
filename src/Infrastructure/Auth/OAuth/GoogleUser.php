<?php

namespace App\Infrastructure\Auth\OAuth;

class GoogleUser {
    public function __construct(
        public readonly string $googleId,
        public readonly string $name,
        public readonly string $email,
    ) {}
}
