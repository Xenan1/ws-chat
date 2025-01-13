<?php

namespace App\Http\Controllers;

use App\Actions\GetUserFeed;
use App\Actions\GetUserRecommendation;
use App\Http\Requests\LikePostRequest;
use App\Http\Resources\FeedResource;
use App\Http\Resources\UserResource;
use App\Http\Responses\CommonResponse;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FeedController extends Controller
{
    public function getFeed(): FeedResource
    {
        $posts = app(GetUserFeed::class)->run(auth()->user()->id);

        return new FeedResource($posts);
    }

    public function likePost(LikePostRequest $request, UserService $service): CommonResponse
    {
        $service->likePost(auth()->user(), $request->getPostId());

        return new CommonResponse(true, 200);
    }

    public function unlikePost(LikePostRequest $request, UserService $service): CommonResponse
    {
        $service->unlikePost(auth()->user(), $request->getPostId());

        return new CommonResponse(true, 204);
    }

    public function getUserRecommendation(): AnonymousResourceCollection
    {
        $users = app(GetUserRecommendation::class)->run(auth()->user()->getId());

        return UserResource::collection($users);
    }
}
