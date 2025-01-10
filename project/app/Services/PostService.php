<?php

namespace App\Services;

use App\DTO\PostDataDTO;
use App\Models\Post;

class PostService
{
    public function createPost(PostDataDTO $postData): Post
    {
        $post = Post::query()->create([
            'text' => $postData->text,
            'user_id' => $postData->authorId,
        ]);

        $post->tags()->attach($postData->tags);

        return $post;
    }
}
