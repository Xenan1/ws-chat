<?php

namespace App\Parsing;

use App\DTO\PostDataDTO;
use App\Logging\Enum\LogLevels;
use App\Logging\ParsingLogger;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Parsing\Exceptions\AbstractParserException;
use App\Services\PostService;
use Illuminate\Support\Collection;

abstract class AbstractParser
{
    public function __construct(protected PostService $postService, protected ParsingLogger $logger) {}

    /**
     * @throws AbstractParserException
     */
    public function createPost(): Post
    {
        $postId = $this->getRandomPostId();
        $this->logger->log(LogLevels::Info, 'Parsing random post');
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

    /**
     * @throws AbstractParserException
     */
    abstract protected function getRandomPostId(): int;

    /**
     * @throws AbstractParserException
     */
    abstract protected function getPostContent(int $postId): string;

    /**
     * @param int $postId
     * @return Collection<Tag>
     * @throws AbstractParserException
     */
    abstract protected function getPostTags(int $postId): Collection;

    abstract protected function getSource(): ParseSources;

    /**
     * @throws AbstractParserException
     */
    public function createPostById(int $postId): Post
    {
        $postContent = $this->getPostContent($postId);
        $this->logger->log(LogLevels::Info, 'Parsing post', ['id' => $postId]);
        $postTags = $this->getPostTags($postId);

        $tagsIds = $postTags->pluck('id')->toArray();
        $postData = new PostDataDTO($postContent, $this->getParsingUser()->getId(), $tagsIds, $postId, $this->getSource());
        return $this->postService->createPost($postData);
    }
}
