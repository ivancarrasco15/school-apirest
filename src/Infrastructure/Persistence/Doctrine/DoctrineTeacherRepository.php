<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\ITeacherRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTeacherRepository implements ITeacherRepository {
    public function __construct(private EntityManagerInterface $em) {}

    public function find(TeacherId $id): ?Teacher {
        return $this->em->find(Teacher::class, $id->value());
    }

    public function save(Teacher $teacher): void {
        $this->em->persist($teacher);
        $this->em->flush();
    }

    public function findAll(): array {
        $teachers = $this->em->getRepository(Teacher::class)->findAll();
        return array_map(fn($t) => $t->toArray(), $teachers);
    }

    public function delete(Teacher $teacher): void {
        $this->em->remove($teacher);
        $this->em->flush();
    }
}
