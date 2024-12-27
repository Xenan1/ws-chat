<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GetUserFeed
{
    /**
     * @param int $userId
     * @return Collection<Post>
     */
    public function run(int $userId): Collection
    {
        $tagWeights = $this->getTagsWeightSubquery($userId);

        return Post::query()
            ->with(['author', 'likes', 'tags', 'author.avatar'])
            ->leftJoin('posts_tags', 'posts.id', '=', 'posts_tags.post_id')
            ->leftJoinSub($tagWeights, 'tag_weights', 'posts_tags.tag_id', '=', 'tag_weights.tag_id')
            ->leftJoin('posts_views', function (JoinClause $join) use ($userId) {
                $join->on('posts.id', '=', 'posts_views.post_id')
                    ->where('posts_views.user_id', '=', $userId);
            })
            ->whereNull('posts_views.id')
            ->select(
                'posts.id',
                'posts.text',
                'posts.user_id',
                'posts.created_at',
                DB::raw('SUM(tag_weights.tag_weight) as total_weight'),
                DB::raw('COUNT(posts_tags.tag_id) as has_tags')
            )
            ->groupBy('posts.id')
            ->orderByDesc('has_tags')
            ->orderByDesc('total_weight')
            ->limit(20)
            ->get();
    }

    public function getTagsWeightSubquery(int $userId): Builder
    {
        return DB::table('posts_tags')
            ->join('likes', 'posts_tags.post_id', '=', 'likes.post_id')
            ->where('likes.user_id', '=', $userId)
            ->join('tags', 'posts_tags.tag_id', '=', 'tags.id')
            ->select(
                'tags.id as tag_id',
                DB::raw('COUNT(likes.id) as tag_weight')
            )
            ->groupBy('tags.id');
    }
}
