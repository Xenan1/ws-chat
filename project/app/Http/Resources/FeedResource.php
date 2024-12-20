<?php

namespace App\Http\Resources;


use App\Models\Post;
use Illuminate\Support\Collection;

class FeedResource extends BaseJsonResource
{
    /**
     * @var Collection<Post>
     */
    protected Collection $posts;

    public function __construct(Collection $posts)
    {
        $this->posts = $posts;
        parent::__construct($posts);
    }

    public function toArray($request): array
    {
        return [
            'posts' => $this->posts->map(function (Post $post) {
                return [
                    'id' => $post->getId(),
                    'author' => new UserResource($post->getAuthor()),
                    'text' => $post->getText(),
                    'likes' => $post->getLikesCount(),
                    'date' => $post->getCreatedAt(),
                ];
            })
        ];
    }
}
