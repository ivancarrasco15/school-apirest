<?php

namespace App\Domain\Teacher;

interface ITeacherRepository
{
    public function save(Teacher $teacher): void;
    public function find(TeacherId $id): ?Teacher;
    public function findAll(): array;
    public function delete(Teacher $teacher): void;
}