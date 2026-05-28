<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 레이아웃
    |--------------------------------------------------------------------------
    | 공개 게시판 페이지(목록·상세·글쓰기)에 적용할 Blade 레이아웃.
    | 앱에서 config/korean-bbs.php를 publish 후 원하는 레이아웃으로 교체하세요.
    | 예: 'layouts.app'
    */
    'layout' => 'korean-bbs::layouts.bbs',

    /*
    |--------------------------------------------------------------------------
    | 관리자 계정
    |--------------------------------------------------------------------------
    | 관리자 아이디와 비밀번호를 .env 파일에서 설정합니다.
    | BBS_ADMIN_ID, BBS_ADMIN_PASSWORD, BBS_ADMIN_NAME
    */
    'admin' => [
        'id'       => env('BBS_ADMIN_ID', 'admin'),
        'password' => env('BBS_ADMIN_PASSWORD', 'admin1234!'),
        'name'     => env('BBS_ADMIN_NAME', '관리자'),
    ],

    /*
    |--------------------------------------------------------------------------
    | 게시판 권한
    |--------------------------------------------------------------------------
    | 게시판의 목록, 상세보기, 글쓰기, 댓글, 파일, 추천 권한 레벨을 해석합니다.
    | 호스트 앱의 등급 체계가 있다면 level_resolver/admin_resolver 클로저로 연결하세요.
    */
    'auth' => [
        'guest_level' => 0,
        'member_level' => 1,
        'admin_level' => 10,
        'level_resolver' => null,
        'admin_resolver' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | URL 프리픽스
    |--------------------------------------------------------------------------
    */
    'prefix' => [
        'web'   => 'bbs',
        'admin' => 'bbs-admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | 파일 업로드 설정
    |--------------------------------------------------------------------------
    */
    'upload' => [
        'disk'          => 'public',
        'path'          => 'bbs/files',
        'max_size'      => 10240, // KB (10MB)
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'zip', 'hwp', 'docx', 'xlsx'],
        'image_types'   => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'thumbnail'     => [
            'width'  => 300,
            'height' => 300,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 기본 설정
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'posts_per_page'    => 20,
        'comments_per_page' => 50,
        'gallery_per_page'  => 12,
    ],

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA 설정
    |--------------------------------------------------------------------------
    | 글 등록/수정 시 CAPTCHA를 사용합니다.
    | guest_only가 true이면 비회원 작성자에게만 적용됩니다.
    */
    'captcha' => [
        'enabled' => true,
        'guest_only' => true,
        'provider' => env('BBS_CAPTCHA_PROVIDER', 'math'), // math | turnstile | recaptcha
        'min' => 1,
        'max' => 9,
        'turnstile' => [
            'site_key' => env('BBS_TURNSTILE_SITE_KEY'),
            'secret_key' => env('BBS_TURNSTILE_SECRET_KEY'),
        ],
        'recaptcha' => [
            'site_key' => env('BBS_RECAPTCHA_SITE_KEY'),
            'secret_key' => env('BBS_RECAPTCHA_SECRET_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 스킨 설정
    |--------------------------------------------------------------------------
    | allowed: 허용된 스킨 키 목록. 앱에서 config를 오버라이드해 추가 가능.
    | default: 기본 스킨 키 (현재 미사용 — 잘못된 키는 예외 발생).
    | path: 앱에서 publish하거나 직접 만든 게시판 스킨 경로.
    |       {path}/{skin}/{list|show|form}.blade.php 형태로 탐색합니다.
    */
    'skins' => [
        'allowed' => ['list', 'gallery', 'custom', 'blog'],
        'default' => 'list',
        'path' => resource_path('views/vendor/korean-bbs/board/skins'),
    ],

    /*
    |--------------------------------------------------------------------------
    | 에디터 설정
    |--------------------------------------------------------------------------
    | default: 기본 글쓰기 에디터. trix, quill, tinymce, textarea를 제공합니다.
    | skins: 스킨별 에디터 오버라이드. 예: ['gallery' => 'quill']
    | allow_source_view: 기본 에디터에서 HTML 소스 편집 버튼 노출 여부.
    | path: 앱에서 직접 만든 에디터 Blade 경로.
    |       {path}/{editor}.blade.php 형태로 탐색합니다.
    */
    'editors' => [
        'default' => 'trix',
        'allow_source_view' => true,
        'skins' => [
            // 'gallery' => 'textarea',
            'list' => 'tinymce',
            'gallery' => 'quill',
            'blog' => 'tinymce',
            // 'custom' => 'my-editor',
        ],
        'path' => resource_path('views/vendor/korean-bbs/editors'),
        'allowed_tags' => [
            'div', 'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'blockquote', 'pre', 'code',
            'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3', 'h4', 'hr',
        ],
    ],

];
