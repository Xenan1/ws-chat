<?php

namespace App\DTO;

readonly class CredentialsDTO
{
    public function __construct(
        public string $login,
        public string $password,
    ) {}

    public function toArray(): array
    {
        return [
            'login' => $this->login,
            'password' => $this->password,
        ];
    }
}
