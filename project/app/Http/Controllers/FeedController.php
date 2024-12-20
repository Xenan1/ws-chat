<?php

namespace App\Http\Controllers;

use App\Actions\GetUserFeed;
use App\Http\Requests\LikePostRequest;
use App\Http\Resources\FeedResource;
use App\Http\Responses\CommonResponse;

class FeedController extends Controller
{
    public function getFeed(): FeedResource
    {
        $posts = app(GetUserFeed::class)->run(auth()->user()->id);

        return new FeedResource($posts);
    }

    public function likePost(LikePostRequest $request): CommonResponse
    {
        auth()->user()->likePost($request->getPostId());

        return new CommonResponse(true, 200);
    }

    public function unlikePost(LikePostRequest $request): CommonResponse
    {
        auth()->user()->unlikePost($request->getPostId());

        return new CommonResponse(true, 204);
    }
}
