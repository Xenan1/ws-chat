<?php

namespace App\DTO;

use App\Http\Requests\RegisterUserRequest;

readonly class UserDTO
{
    public function __construct(
        public string $login,
        public string $name,
        public string $password,
    ) {}

    public static function fromRequest(RegisterUserRequest $request): static
    {
        $data = $request->validated();
        return new self($data['login'], $data['name'], $data['password']);
    }

    public function toArray(): array
    {
        return [
            'login'=> $this->login,
            'name'=> $this->name,
            'password'=> $this->password,
        ];
    }

    public function getCredentials(): CredentialsDTO
    {
        return new CredentialsDTO($this->login, $this->password);
    }
}
