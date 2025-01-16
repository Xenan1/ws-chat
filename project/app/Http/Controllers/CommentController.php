<?php

namespace App\Http\Controllers;

use App\DTO\CommentDataDTO;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Responses\CommonResponse;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function __construct(protected CommentService $commentService) {}

    public function create(int $postId, CreateCommentRequest $request): CommonResponse
    {
        $comment = new CommentDataDTO($request->getText(), auth()->user()->getId(), $postId);
        $this->commentService->createComment($comment);

        return new CommonResponse(true, 201);
    }
}
