<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\ISubjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSubjectRepository implements ISubjectRepository {
    public function __construct(private EntityManagerInterface $em) {}

    public function find(SubjectId $id): ?Subject {
        return $this->em->find(Subject::class, $id->value());
    }

    public function save(Subject $subject): void {
        $this->em->persist($subject);
        $this->em->flush();
    }

    public function findAll(): array {
        $subjects = $this->em->getRepository(Subject::class)->findAll();
        return array_map(fn($s) => $s->toArray(), $subjects);
    }

    public function delete(Subject $subject): void {
        $this->em->remove($subject);
        $this->em->flush();
    }
}
