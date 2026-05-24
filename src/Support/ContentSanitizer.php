<?php

namespace Ssh521\KoreanBbs\Support;

class ContentSanitizer
{
    public static function clean(?string $content): string
    {
        $content = (string) $content;

        if ($content === '') {
            return '';
        }

        $content = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', '', $content) ?? '';

        $allowedTags = config('korean-bbs.editors.allowed_tags', [
            'div', 'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'blockquote', 'pre', 'code',
            'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3', 'h4', 'hr',
        ]);

        $allowed = implode('', array_map(fn ($tag) => "<{$tag}>", $allowedTags));
        $content = strip_tags($content, $allowed);

        $content = preg_replace_callback('/<a\b([^>]*)>/i', function (array $matches): string {
            if (!preg_match('/\shref\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', $matches[1], $hrefMatch)) {
                return '<a>';
            }

            $href = trim($hrefMatch[1], " \t\n\r\0\x0B\"'");
            if (!preg_match('/^(https?:|mailto:|tel:|\/|#)/i', $href)) {
                return '<a>';
            }

            return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">';
        }, $content) ?? '';

        $content = preg_replace('/<((?!a\b)[a-z][a-z0-9]*)\b[^>]*>/i', '<$1>', $content) ?? '';

        return trim($content);
    }
}
