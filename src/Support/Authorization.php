<?php

namespace Ssh521\KoreanBbs\Support;

use Illuminate\Contracts\Auth\Authenticatable;

class Authorization
{
    public static function isAdmin(?Authenticatable $user = null): bool
    {
        $resolver = config('korean-bbs.auth.admin_resolver');

        if (is_callable($resolver)) {
            return (bool) $resolver($user);
        }

        return (bool) session('bbs_admin_authenticated');
    }

    public static function userLevel(?Authenticatable $user = null): int
    {
        if (self::isAdmin($user)) {
            return (int) config('korean-bbs.auth.admin_level', 10);
        }

        $resolver = config('korean-bbs.auth.level_resolver');

        if (is_callable($resolver)) {
            return (int) $resolver($user);
        }

        if (!$user) {
            return (int) config('korean-bbs.auth.guest_level', 0);
        }

        return (int) config('korean-bbs.auth.member_level', 1);
    }

    public static function hasLevel(?Authenticatable $user, int $requiredLevel): bool
    {
        return self::userLevel($user) >= $requiredLevel;
    }
}
