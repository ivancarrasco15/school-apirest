<?php

namespace App\Domain\Subject;

final class SubjectId
{
    private string $id;

    public function __construct(string $id)
    {
        if (empty(trim($id))) {
            throw new \InvalidArgumentException('SubjectId cannot be empty');
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