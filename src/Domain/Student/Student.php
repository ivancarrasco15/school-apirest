<?php

namespace App\Domain\Student;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
#[Table(name: 'students')]
class Student
{
    #[Id]
    #[Column(type: 'string', length: 36)]
    private string $id;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'string', unique: true)]
    private string $email;

    public function __construct(StudentId $id, string $name, string $email)
    {
        $this->id = (string) $id;
        $this->setName($name);
        $this->setEmail($email);
    }

    public function id(): StudentId
    {
        return new StudentId($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function setName(string $name): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        if (empty(trim($email))) {
            throw new \InvalidArgumentException('Email cannot be empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email is not valid');
        }

        $this->email = $email;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}