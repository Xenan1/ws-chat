<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Http\Responses\CommonResponse;
use App\Services\UserService;

class SubscriptionController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function subscribe(SubscriptionRequest $request): CommonResponse
    {
        $this->userService->subscribe(auth()->user(), $request->getAuthorId());

        return new CommonResponse(true, 200);
    }

    public function unsubscribe(SubscriptionRequest $request): CommonResponse
    {
        $this->userService->unsubscribe(auth()->user(), $request->getAuthorId());

        return new CommonResponse(true, 204);
    }
}
