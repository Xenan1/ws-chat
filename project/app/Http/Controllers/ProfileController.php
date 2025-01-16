<?php

namespace App\Http\Controllers;

use App\DTO\DeviceTokenDataDTO;
use App\Http\Requests\SetDeviceTokenRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Responses\CommonResponse;
use App\Services\DeviceTokenService;
use App\Services\UploadService;
use App\Services\UserService;

class ProfileController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected UploadService $uploadService,
        protected DeviceTokenService $tokenService,
    ) {}

    public function uploadAvatar(UploadImageRequest $request): CommonResponse
    {
        $user = auth()->user();
        $avatarPath = $this->uploadService->uploadImage($request->getImage(), "users/$user->id/avatar");
        $this->userService->setAvatar($user, $avatarPath);

        return new CommonResponse(true, 200);
    }

    public function addDeviceToken(SetDeviceTokenRequest $request): CommonResponse
    {
        $deviceTokenDataDTO = new DeviceTokenDataDTO(auth()->user()->getId(), $request->getDeviceToken());
        $this->tokenService->create($deviceTokenDataDTO);

        return new CommonResponse(true, 201);
    }
}
