<?php

namespace App\Cache;

class CacheKeyStorage
{
    public static function dialog(int $userId, int $chatPartnerId): CacheKey
    {
        $userIds = [$userId, $chatPartnerId];
        sort($userIds);
        $key = sprintf('dialog_user%d_user%d', $userIds[0], $userIds[1]);
        return new CacheKey($key);
    }
}
