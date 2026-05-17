<?php

namespace App\Infrastructure\Auth\OAuth;

class GoogleOAuthClient {
    private array $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../../../../config/oauth.php';
        $this->config = $this->config['google'];
    }

    public function getAuthUrl(): string {
        $params = http_build_query([
            'client_id'     => $this->config['client_id'],
            'redirect_uri'  => $this->config['redirect_uri'],
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'access_type'   => 'online',
        ]);
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . $params;
    }

    public function fetchUser(string $code): GoogleUser {
        $tokenResponse = $this->post('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'redirect_uri'  => $this->config['redirect_uri'],
            'grant_type'    => 'authorization_code',
        ]);

        if (empty($tokenResponse['access_token'])) {
            $error = $tokenResponse['error_description'] ?? $tokenResponse['error'] ?? 'Unknown error';
            throw new \RuntimeException('Google token error: ' . $error);
        }

        $userInfo = $this->get(
            'https://www.googleapis.com/oauth2/v3/userinfo',
            $tokenResponse['access_token']
        );

        if (empty($userInfo['sub'])) {
            throw new \RuntimeException('Could not get user info from Google');
        }

        return new GoogleUser(
            googleId: $userInfo['sub'],
            name:     $userInfo['name'] ?? $userInfo['email'],
            email:    $userInfo['email'],
        );
    }

    private function post(string $url, array $data): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        $result = curl_exec($ch);
        if ($result === false) {
            throw new \RuntimeException('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode($result, true) ?? [];
    }

    private function get(string $url, string $token): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => ["Authorization: Bearer $token"],
        ]);
        $result = curl_exec($ch);
        if ($result === false) {
            throw new \RuntimeException('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode($result, true) ?? [];
    }
}
