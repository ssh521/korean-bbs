<?php

namespace Ssh521\KoreanBbs;

use InvalidArgumentException;

class SkinResolver
{
    private const KEY_PATTERN = '/^[a-z][a-z0-9]*(-[a-z0-9]+)*$/';

    public static function resolve(string $skin): string
    {
        $allowed = config('korean-bbs.skins.allowed', ['list', 'gallery']);

        if (!preg_match(self::KEY_PATTERN, $skin)) {
            throw new InvalidArgumentException("Invalid skin key format: [{$skin}]");
        }

        if (!in_array($skin, $allowed, true)) {
            throw new InvalidArgumentException("Skin key [{$skin}] is not in the allowed list.");
        }

        $view = "korean-bbs::board.{$skin}";

        if (!view()->exists($view)) {
            throw new InvalidArgumentException("Skin view [{$view}] does not exist.");
        }

        return $view;
    }
}
