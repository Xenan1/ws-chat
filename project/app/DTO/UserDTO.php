<?php

namespace App\DTO;

use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;

readonly class UserDTO
{
    public function __construct(
        public string $login,
        public string $name,
        public string $password,
        public ?string $referralLink = null,
    ) {}

    public static function fromRequest(RegisterUserRequest $request): static
    {
        return new static($request->getLogin(), $request->getName(), $request->getPassword(), $request->getReferral());
    }

    public function toCreatableArray(): array
    {
        $userService = app(UserService::class);
        $referrer = $userService->getReferralLinkUser($this->referralLink);

        return [
            'login'=> $this->login,
            'name'=> $this->name,
            'password'=> bcrypt($this->password),
            'referrer_id' => $referrer?->getId(),
            'referral_link' => $userService->generateReferralLink($this->login),
        ];
    }

    public function getCredentials(): CredentialsDTO
    {
        return new CredentialsDTO($this->login, $this->password);
    }
}
