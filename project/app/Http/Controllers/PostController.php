<?php

namespace App\Http\Controllers;

use App\DTO\PostDataDTO;
use App\Http\Requests\CreatePostRequest;
use App\Http\Responses\CommonResponse;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(protected PostService $postService) {}

    public function createPost(CreatePostRequest $request): CommonResponse
    {
        $postData = new PostDataDTO(
            $request->getText(),
            auth()->user()->id,
            $request->getTags(),
        );

        $this->postService->createPost($postData);

        return new CommonResponse(true, 201);
    }
}
