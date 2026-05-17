<?php

namespace App\Http\Controllers;

use App\Auth\Domain\User;
use App\Http\Request;
use App\Http\Responsejson;
use App\Infrastructure\Auth\OAuth\GoogleOAuthClient;
use App\Infrastructure\Auth\Token\JwtTokenGenerator;
use App\Infrastructure\Persistence\Doctrine\DoctrineAuthRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthController {
    private GoogleOAuthClient $googleClient;
    private JwtTokenGenerator $jwtGenerator;
    private DoctrineAuthRepository $authRepo;

    public function __construct(Request $request, EntityManagerInterface $em) {
        $this->googleClient = new GoogleOAuthClient();
        $this->jwtGenerator = new JwtTokenGenerator();
        $this->authRepo = new DoctrineAuthRepository($em);
    }

    public function login(): void {
        header('Location: ' . $this->googleClient->getAuthUrl());
        exit;
    }

    public function callback(): void {
        $code = $_GET['code'] ?? null;

        if (!$code) {
            (new Responsejson(400, ['msg' => 'Missing code']))->send();
            return;
        }

        try {
            $googleUser = $this->googleClient->fetchUser($code);
            $user = $this->authRepo->findByGoogleId($googleUser->googleId);

            if (!$user) {
                $user = new User(
                    id: bin2hex(random_bytes(16)),
                    name: $googleUser->name,
                    email: $googleUser->email,
                    googleId: $googleUser->googleId,
                );
                $this->authRepo->save($user);
            }

            $token = $this->jwtGenerator->generate($user->toArray());
            $clientUrl = $_ENV['CLIENT_REDIRECT_URL'] . '?token=' . urlencode($token);
            header('Location: ' . $clientUrl);
            exit;

        } catch (\Throwable $e) {
            (new Responsejson(500, ['msg' => 'OAuth error: ' . $e->getMessage()]))->send();
        }
    }
}
