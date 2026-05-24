<?php

namespace Ssh521\KoreanBbs;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use InvalidArgumentException;

class EditorResolver
{
    private const CUSTOM_NAMESPACE = 'korean-bbs-editors';
    private const KEY_PATTERN = '/^[a-z0-9_-]+$/';

    public static function resolve(?string $skin = null): string
    {
        $editor = self::editorKey($skin);

        if (!preg_match(self::KEY_PATTERN, $editor)) {
            throw new InvalidArgumentException("Invalid editor key format: [{$editor}]");
        }

        $customPath = config('korean-bbs.editors.path');
        if ($customPath) {
            View::addNamespace(self::CUSTOM_NAMESPACE, $customPath);

            if (File::exists($customPath . "/{$editor}.blade.php")) {
                return self::CUSTOM_NAMESPACE . "::{$editor}";
            }
        }

        $view = "korean-bbs::editors.{$editor}";
        if (View::exists($view)) {
            return $view;
        }

        return 'korean-bbs::editors.textarea';
    }

    public static function contentView(): string
    {
        return 'korean-bbs::editors.content';
    }

    public static function editorKey(?string $skin = null): string
    {
        $skinEditors = config('korean-bbs.editors.skins', []);

        if ($skin && isset($skinEditors[$skin])) {
            return $skinEditors[$skin];
        }

        return config('korean-bbs.editors.default', 'trix');
    }
}
