<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\IStudentRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineStudentRepository implements IStudentRepository
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function find(StudentId $id): ?Student
    {
        $student = $this->em->find(Student::class, $id->value());
        return $student;
    }

    public function save(Student $student): void
    {
        $this->em->persist($student);
        $this->em->flush();
    }

    public function findAll(): array
    {
        $repository = $this->em->getRepository(Student::class);
        $students = $repository->findAll();

        $data = array_map(fn($student) => $student->toArray(), $students);
        return $data;
    }

    public function delete(Student $student): void
    {
        $this->em->remove($student);
        $this->em->flush();
    }
}