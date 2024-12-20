<?php

namespace App\Http\Controllers;

use App\Actions\GetUserFeed;
use App\Http\Resources\FeedResource;

class FeedController extends Controller
{
    public function getFeed(): FeedResource
    {
        $posts = app(GetUserFeed::class)->run(auth()->user()->id);

        return new FeedResource($posts);
    }
}
