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
            'parsed_id' => $postData->parsedId,
            'parsed_source' => $postData->parsedSource->value,
        ]);

        $post->tags()->attach($postData->tags);

        return $post;
    }
}
