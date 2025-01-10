<?php

namespace App\Services;

use App\DTO\CommentDataDTO;
use App\Models\Comment;

class CommentService
{
    public function createComment(CommentDataDTO $commentData): Comment
    {
        return Comment::query()->create([
            'text' => $commentData->text,
            'post_id' => $commentData->postId,
            'author_id' => $commentData->authorId
        ]);
    }
}
