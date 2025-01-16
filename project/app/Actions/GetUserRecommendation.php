<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GetUserRecommendation
{
    public function run(int $userId): Collection
    {
        $tagWeights = $this->getTagsWeightSubquery($userId);

        $aggregatedUserTags = DB::table('likes as l1')
            ->join('posts_tags as pt1', 'l1.post_id', '=', 'pt1.post_id')
            ->joinSub($tagWeights, 'tag_weights', 'pt1.tag_id', '=', 'tag_weights.tag_id')
            ->where('l1.user_id', '=', $userId)
            ->select(
                'pt1.tag_id',
                DB::raw('SUM(tag_weights.tag_weight) as tag_weight'),
                DB::raw('COUNT(pt1.post_id) as post_count')
            )
            ->groupBy('pt1.tag_id');

        $recommendedUsers = DB::table('likes as l2')
            ->join('posts_tags as pt2', 'l2.post_id', '=', 'pt2.post_id')
            ->joinSub($aggregatedUserTags, 'user_tags', 'pt2.tag_id', '=', 'user_tags.tag_id')
            ->where('l2.user_id', '!=', $userId)
            ->select(
                'l2.user_id',
                DB::raw('SUM(user_tags.tag_weight) as total_weight'),
                DB::raw('COUNT(DISTINCT pt2.tag_id) as shared_tags')
            )
            ->groupBy('l2.user_id')
            ->orderByDesc('total_weight')
            ->limit(20)
            ->get();

        $orderedIds = $recommendedUsers->pluck('user_id')->toArray();

        return User::with('image')
            ->whereIn('id', $orderedIds)
            ->orderByRaw('FIELD(id, ' . implode(',', $orderedIds) . ')')
            ->get();
    }

    public function getTagsWeightSubquery(int $userId): Builder
    {
        return DB::table('posts_tags as pt')
            ->join('likes as l', 'pt.post_id', '=', 'l.post_id')
            ->where('l.user_id', '=', $userId)
            ->join('tags as t', 'pt.tag_id', '=', 't.id')
            ->select(
                't.id as tag_id',
                DB::raw('COUNT(l.id) as tag_weight')
            )
            ->groupBy('t.id');
    }
}
