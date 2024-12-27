<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\Http\Responses\CommonResponse;
use App\Services\UploadService;
use App\Services\UserService;

class ProfileController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected UploadService $uploadService,
    ) {}

    public function uploadAvatar(UploadImageRequest $request): CommonResponse
    {
        $user = auth()->user();
        $avatarPath = $this->uploadService->uploadImage($request->getImage(), "users/$user->id/avatar");
        $this->userService->setAvatar($user, $avatarPath);

        return new CommonResponse(true, 200);
    }
}
