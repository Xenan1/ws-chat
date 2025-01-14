<?php

namespace App\Parsing;

use App\DTO\PostDataDTO;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Support\Collection;

abstract class AbstractParser
{
    public function __construct(protected PostService $postService) {}

    public function createPost(): Post
    {
        $postId = $this->getRandomPostId();
        return $this->createPostById($postId);
    }

    protected function getParsingUser(): User
    {
        return User::query()->firstOrCreate([
            'login' => 'parseUser',
            'name' => 'Обычный пользователь',
        ], [
            'login' => 'parseUser',
            'name' => 'Обычный пользователь',
            'password' => bcrypt('hardpassword'),
        ]);
    }

    abstract protected function getRandomPostId(): int;

    abstract protected function getPostContent(int $postId): string;

    /**
     * @param int $postId
     * @return Collection<Tag>
     */
    abstract protected function getPostTags(int $postId): Collection;

    public function createPostById(int $postId): Post
    {
        $postContent = $this->getPostContent($postId);
        $postTags = $this->getPostTags($postId);

        $tagsIds = $postTags->pluck('id')->toArray();
        $postData = new PostDataDTO($postContent, $this->getParsingUser()->getId(), $tagsIds);
        return $this->postService->createPost($postData);
    }
}
