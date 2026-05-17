<?php

namespace App\Auth\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'auth_users')]
class User {
    #[Id]
    #[Column(type: 'string', length: 36)]
    private string $id;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'string', unique: true)]
    private string $email;

    #[Column(name: 'google_id', type: 'string', unique: true)]
    private string $googleId;

    public function __construct(string $id, string $name, string $email, string $googleId) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->googleId = $googleId;
    }

    public function id(): string { return $this->id; }
    public function name(): string { return $this->name; }
    public function email(): string { return $this->email; }

    public function toArray(): array {
        return ['id' => $this->id, 'name' => $this->name, 'email' => $this->email];
    }
}
