<?php

namespace App\Domain\Subject;

interface ISubjectRepository
{
    public function save(Subject $subject): void;
    public function find(SubjectId $id): ?Subject;
    public function findAll(): array;
    public function delete(Subject $subject): void;
}