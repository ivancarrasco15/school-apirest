<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Auth\Domain\AuthRepository;
use App\Auth\Domain\User;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineAuthRepository implements AuthRepository {
    public function __construct(private EntityManagerInterface $em) {}

    public function findByGoogleId(string $googleId): ?User {
        return $this->em->getRepository(User::class)
            ->findOneBy(['googleId' => $googleId]);
    }

    public function save(User $user): void {
        $this->em->persist($user);
        $this->em->flush();
    }
}
