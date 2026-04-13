<?php

namespace App\Domain\Teacher;

final class TeacherId
{
    private string $id;

    public function __construct(string $id)
    {
        if (empty(trim($id))) {
            throw new \InvalidArgumentException('TeacherId cannot be empty');
        }

        $this->id = $id;
    }

    public function value(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}