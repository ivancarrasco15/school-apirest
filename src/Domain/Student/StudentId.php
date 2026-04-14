<?php

namespace App\Domain\Student;

final class StudentId
{
    private string $id;

    public function __construct(string $id)
    {
        if (empty(trim($id))) {
            throw new \InvalidArgumentException('StudentId cannot be empty');
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