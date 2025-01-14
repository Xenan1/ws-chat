<?php

namespace App\Parsing;

use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class HabrParser extends AbstractParser
{
    private static string $articleListMethod = 'https://habr.com/kek/v2/articles/?sort=rating';
    private static string $articleMethod = 'https://habr.com/kek/v2/articles/%s/?fl=ru&hl=ru';

    protected function getRandomPostId(): int
    {
        $posts = Http::get(self::$articleListMethod)->json();
        return array_rand($posts['publicationRefs']);
    }

    protected function getPostContent(int $postId): string
    {
        return $this->getPostById($postId)['textHtml'];
    }

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

    private function getPostById(int $postId): mixed
    {
        $articleUrl = sprintf(self::$articleMethod, $postId);
        return Http::get($articleUrl)->json();
    }
}
