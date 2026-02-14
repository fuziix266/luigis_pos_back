<?php

namespace Auth\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $secret;
    private int $expiration;

    public function __construct(string $secret, int $expiration = 86400)
    {
        $this->secret = $secret;
        $this->expiration = $expiration;
    }

    public function generateToken(array $payload): string
    {
        $issued = time();
        $data = array_merge($payload, [
            'iat' => $issued,
            'exp' => $issued + $this->expiration,
        ]);
        return JWT::encode($data, $this->secret, 'HS256');
    }

    public function validateToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}
