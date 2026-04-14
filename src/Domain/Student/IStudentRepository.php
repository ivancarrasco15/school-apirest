<?php

namespace App\Domain\Student;

interface IStudentRepository
{
    public function save(Student $student): void;
    public function find(StudentId $id): ?Student;
    public function findAll(): array;
    public function delete(Student $student): void;
}