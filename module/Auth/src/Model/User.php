<?php

namespace Auth\Model;

class User
{
    public int $id;
    public string $username;
    public string $password_hash;
    public string $full_name;
    public string $role;
    public bool $is_active;
    public ?string $created_at;

    public function exchangeArray(array $data): void
    {
        $this->id            = (int) ($data['id'] ?? 0);
        $this->username      = $data['username'] ?? '';
        $this->password_hash = $data['password_hash'] ?? '';
        $this->full_name     = $data['full_name'] ?? '';
        $this->role          = $data['role'] ?? 'cajero';
        $this->is_active     = (bool) ($data['is_active'] ?? true);
        $this->created_at    = $data['created_at'] ?? null;
    }

    public function getArrayCopy(): array
    {
        return [
            'id'        => $this->id,
            'username'  => $this->username,
            'full_name' => $this->full_name,
            'role'      => $this->role,
            'is_active' => $this->is_active,
        ];
    }
}
