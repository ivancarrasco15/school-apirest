<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\ITeacherRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTeacherRepository implements ITeacherRepository
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function find(TeacherId $id): ?Teacher
    {
        $teacher = $this->em->find(Teacher::class, $id->value());
        return $teacher;
    }

    public function save(Teacher $teacher): void
    {
        $this->em->persist($teacher);
        $this->em->flush();
    }

    public function findAll(): array
    {
        $repository = $this->em->getRepository(Teacher::class);
        $teachers = $repository->findAll();

        $data = array_map(fn($teacher) => $teacher->toArray(), $teachers);
        return $data;
    }

    public function delete(Teacher $teacher): void
    {
        $this->em->remove($teacher);
        $this->em->flush();
    }
}