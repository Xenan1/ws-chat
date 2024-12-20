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
                    'id' => $post->id,
                    'author' => new UserResource($post->author),
                    'text' => $post->text,
                    'likes' => $post->getLikesCount(),
                ];
            })
        ];
    }
}
