<?php

namespace App\Auth\Domain;

interface AuthRepository {
    public function findByGoogleId(string $googleId): ?User;
    public function save(User $user): void;
}
