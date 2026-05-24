@php
    $content = (string) ($content ?? '');
    $plainText = $content === strip_tags($content);
@endphp

@once
    <style>
        .korean-bbs-content blockquote {
            margin: 1rem 0;
            padding: 0.75rem 1rem;
            border-left: 4px solid #d1d5db;
            background: #f9fafb;
            color: #4b5563;
        }

        .korean-bbs-content ul,
        .korean-bbs-content ol {
            margin: 0.75rem 0;
            padding-left: 1.5rem;
        }

        .korean-bbs-content ul {
            list-style: disc;
        }

        .korean-bbs-content ol {
            list-style: decimal;
        }

        .korean-bbs-content li {
            display: list-item;
            margin: 0.25rem 0;
        }

        .korean-bbs-content p,
        .korean-bbs-content div {
            margin: 0.5rem 0;
        }

        .korean-bbs-content pre {
            margin: 1rem 0;
            padding: 1rem;
            overflow-x: auto;
            border-radius: 0.5rem;
            background: #111827;
            color: #f9fafb;
        }

        .korean-bbs-content code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 0.875em;
        }
    </style>
@endonce

<div class="korean-bbs-content">
    @if($plainText)
        {!! nl2br(e($content)) !!}
    @else
        {!! \Ssh521\KoreanBbs\Support\ContentSanitizer::clean($content) !!}
    @endif
</div>
