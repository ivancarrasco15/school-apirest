<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\IStudentRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineStudentRepository implements IStudentRepository {
    public function __construct(private EntityManagerInterface $em) {}

    public function find(StudentId $id): ?Student {
        return $this->em->find(Student::class, $id->value());
    }

    public function save(Student $student): void {
        $this->em->persist($student);
        $this->em->flush();
    }

    public function findAll(): array {
        $students = $this->em->getRepository(Student::class)->findAll();
        return array_map(fn($s) => $s->toArray(), $students);
    }

    public function delete(Student $student): void {
        $this->em->remove($student);
        $this->em->flush();
    }
}
