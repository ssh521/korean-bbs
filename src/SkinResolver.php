<?php

namespace Ssh521\KoreanBbs;

use InvalidArgumentException;
use Illuminate\Support\Facades\View;

class SkinResolver
{
    private const KEY_PATTERN = '/^[a-z][a-z0-9]*(-[a-z0-9]+)*$/';
    private const CUSTOM_NAMESPACE = 'korean-bbs-skins';

    /**
     * 스킨 키와 뷰 타입을 검증하고 뷰 이름을 반환한다.
     *
     * @param  string  $skin  허용된 스킨 키 (예: list, gallery, custom)
     * @param  string  $type  뷰 종류: list | show | form
     * @return string         뷰 이름 (예: korean-bbs-skins::list.list, korean-bbs::board.skins.list.list)
     *
     * @throws InvalidArgumentException 키 형식 위반, 허용 목록 미포함, 뷰 파일 미존재
     */
    public static function resolve(string $skin, string $type = 'list'): string
    {
        $allowed = config('korean-bbs.skins.allowed', ['list', 'gallery']);
        $types = ['list', 'show', 'form'];

        if (!preg_match(self::KEY_PATTERN, $skin)) {
            throw new InvalidArgumentException("Invalid skin key format: [{$skin}]");
        }

        if (!in_array($type, $types, true)) {
            throw new InvalidArgumentException("Invalid skin view type: [{$type}]");
        }

        if (!in_array($skin, $allowed, true)) {
            throw new InvalidArgumentException("Skin key [{$skin}] is not in the allowed list.");
        }

        $customPath = config('korean-bbs.skins.path');
        if (is_string($customPath) && $customPath !== '') {
            View::addNamespace(self::CUSTOM_NAMESPACE, $customPath);

            $customView = self::CUSTOM_NAMESPACE . "::{$skin}.{$type}";
            if (view()->exists($customView)) {
                return $customView;
            }
        }

        $view = "korean-bbs::board.skins.{$skin}.{$type}";

        if (!view()->exists($view)) {
            throw new InvalidArgumentException("Skin view [{$view}] does not exist.");
        }

        return $view;
    }
}
