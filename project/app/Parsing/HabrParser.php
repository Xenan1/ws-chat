<?php

namespace App\Parsing;

use App\Logging\Enum\LogLevels;
use App\Models\Post;
use App\Models\Tag;
use App\Parsing\Exceptions\ParseSourceUnavailableException;
use App\Parsing\Exceptions\WrongParseEntityException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class HabrParser extends AbstractParser
{
    private static string $articleListMethod = 'https://habr.com/kek/v2/articles/?sort=rating&page=';
    private static string $postMethod = 'https://habr.com/kek/v2/articles/%s/?fl=ru&hl=ru';

    /**
     * @throws ParseSourceUnavailableException|WrongParseEntityException
     */
    public function handlePostResponse(Response $post, int $postId): array|false
    {
        if ($post->successful()) {
            return $post->json();
        } else if ($post->status() === 404) {
            $this->logger->log(LogLevels::Warning, 'Tried to parse post with wrong id', ['id' => $postId]);
            throw new WrongParseEntityException('There is no post with such id', 404);
        } else {
            $this->throwServiceUnavailable();
        }
    }

    /**
     * @throws ParseSourceUnavailableException
     */
    public function throwServiceUnavailable(): void
    {
        $this->logger->log(LogLevels::Error, 'Habr is not available now');
        throw new ParseSourceUnavailableException('Parse source is unavailable now', 503);
    }

    /**
     * @throws ParseSourceUnavailableException
     */
    protected function getRandomPostId(): int
    {
        $habrPostsIds = Post::query()->where('parsed_source', '=', $this->getSource()->value)->pluck('parsed_id')->toArray();

        $page = 1;
        do {
            $posts = $this->getArticleList($page)->json();
            $page++;
            $notPresentedPosts = array_diff($posts['publicationIds'], $habrPostsIds);
        } while (empty($notPresentedPosts));

        return array_shift($notPresentedPosts);
    }

    /**
     * @throws ParseSourceUnavailableException
     */
    private function getArticleList(int $page): Response
    {
        $posts = Http::get(self::$articleListMethod . $page);

        if (!$this->isValidPostListResponse($posts)) {
            $this->throwServiceUnavailable();
        }

        return $posts;
    }

    /**
     * @throws ParseSourceUnavailableException|WrongParseEntityException
     */
    protected function getPostContent(int $postId): string
    {
        return $this->getPostById($postId)['textHtml'];
    }

    /**
     * @throws ParseSourceUnavailableException|WrongParseEntityException
     */
    protected function getPostTags(int $postId): Collection
    {
        $post = $this->getPostById($postId);

        $postTitles = array_map(function (array $postFlow) {
            return $postFlow['title'];
        }, $post['flows']);

        return collect($postTitles)->map(function (string $tagTitle) {
            return Tag::query()->firstOrCreate(['name'=> $tagTitle]);
        });
    }

    /**
     * @throws ParseSourceUnavailableException|WrongParseEntityException
     */
    private function getPostById(int $postId): array
    {
        $postUrl = sprintf(self::$postMethod, $postId);
        $post =  Http::get($postUrl);

        return $this->handlePostResponse($post, $postId);
    }

    /**
     * @param Response $posts
     * @return bool
     */
    private function isValidPostListResponse(Response $posts): bool
    {
        return !($posts->successful() && isset($posts->json()['publicationsRefs']));
    }

    protected function getSource(): ParseSources
    {
        return ParseSources::Habr;
    }
}
