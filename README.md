# Korean BBS (한국형 게시판)

Laravel · Livewire · TailwindCSS 기반 게시판 패키지입니다. 그누보드5·KBoard에 가까운 구성으로 다중 게시판·갤러리·첨부·댓글 등을 Laravel 앱에 붙일 수 있습니다.

## 요구 사항

- PHP `^8.2`
- Laravel `^11|^12|^13` (`illuminate/support`)
- Livewire `^3`

## 설치

### Composer

로컬 개발(path)인 경우 앱의 `composer.json`에 저장소를 추가한 뒤 설치합니다.

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/ssh521/korean-bbs"
        }
    ],
    "require": {
        "ssh521/korean-bbs": "*"
    }
}
```

```bash
composer update ssh521/korean-bbs
```

### 퍼블리시 및 DB

```bash
php artisan vendor:publish --tag=korean-bbs-config
php artisan migrate
php artisan storage:link
```

테스트용 샘플 게시판/게시글/댓글이 필요하면 패키지 시더를 실행합니다.

```bash
php artisan db:seed --class="Ssh521\\KoreanBbs\\Database\\Seeders\\KoreanBbsTestSeeder"
```

앱의 `database/seeders`로 복사해 수정하고 싶다면 다음 명령을 사용합니다.

```bash
php artisan vendor:publish --tag=korean-bbs-seeders
php artisan db:seed --class="Database\\Seeders\\KoreanBbsTestSeeder"
```

### 환경 변수 (관리자)

`config` 퍼블리시 후 `.env`에 설정합니다.

```env
BBS_ADMIN_ID=admin
BBS_ADMIN_PASSWORD=your_secure_password
BBS_ADMIN_NAME=관리자
```

## URL

| 경로 | 설명 |
|------|------|
| `/bbs` | 게시판 목록 |
| `/bbs/{slug}` | 해당 게시판 글 목록 |
| `/bbs/{slug}/{id}` | 글 상세 |
| `/bbs/{slug}/create` | 글 작성 |
| `/bbs-admin` | 관리자 대시보드 |
| `/bbs-admin/login` | 관리자 로그인 |

프리픽스는 `config/korean-bbs.php`의 `prefix.web`, `prefix.admin`으로 바꿀 수 있습니다.

## 주요 기능

- 다중 게시판(그룹)
- 타입: 일반(목록), 갤러리(썸네일 그리드)
- 글 CRUD, 공지·비밀글, 비회원 글쓰기
- 댓글·대댓글, 첨부(이미지 미리보기·썸네일), 추천/비추천
- 글 등록·수정 CAPTCHA(수학 문제, Cloudflare Turnstile, Google reCAPTCHA)
- Laravel 기본 인증·패키지 관리자(config·세션)

## 설정 개요

설정 파일: `config/korean-bbs.php` (퍼블리시 후 앱에서 수정).

| 항목 | 설명 |
|------|------|
| `layout` | 공개 페이지 Blade 레이아웃 (예: `layouts.app`으로 교체) |
| `admin` | `.env`의 `BBS_ADMIN_*`와 연동되는 관리자 계정 |
| `auth` | 게시판별 글쓰기·댓글·파일 권한 레벨 해석 방식 |
| `prefix` | 웹·관리자 URL 접두사 |
| `upload` | 디스크, 경로, 최대 크기(KB), 허용 확장자, 썸네일 크기 |
| `defaults` | 페이지당 글·댓글·갤러리 개수 |
| `skins` | 허용 스킨 키, 기본 스킨, 커스텀 스킨 경로 |
| `editors` | 기본 글쓰기 에디터, 스킨별 에디터, 커스텀 에디터 경로 |
| `captcha` | 글 등록·수정 CAPTCHA 사용 여부, 제공자, 비회원 전용 여부 |

게시판 권한은 관리자 화면의 `list_level`, `read_level`, `write_level`, `comment_level`, `upload_level`, `download_level`, `like_level` 값을 기준으로 동작합니다.
기본값은 비회원 `0`, 로그인 회원 `1`, 관리자 `10`이며, 앱의 회원 등급 체계가 다르면 `auth.level_resolver` 또는 `auth.admin_resolver`를 설정해 연결할 수 있습니다.

게시판별 공개 화면 폭은 관리자 게시판 설정의 `게시판 width`에서 지정할 수 있습니다.
TailwindCSS 클래스와 CSS width 값을 모두 지원합니다.

| 입력 예시 | 설명 |
|----------|------|
| `max-w-6xl` | Tailwind 최대 폭 |
| `w-full` | 부모 영역 전체 폭 |
| `w-[720px]` | Tailwind arbitrary value |
| `100%` | 예전 스타일의 CSS width |
| `600px` | 고정 픽셀 폭 |
| `48rem` | rem 기반 폭 |

## 뷰 커스터마이징

전체 패키지 뷰를 publish하려면 다음 명령을 사용합니다.

```bash
php artisan vendor:publish --tag=korean-bbs-views
```

Blade는 `resources/views/vendor/korean-bbs/`에 복사됩니다.

게시판 스킨만 publish하려면 다음 명령을 사용합니다.

```bash
php artisan vendor:publish --tag=korean-bbs-skins
```

스킨은 기본적으로 `resources/views/vendor/korean-bbs/board/skins`에 복사됩니다.
경로를 바꾸고 싶다면 `config/korean-bbs.php`에서 `skins.path`를 수정하세요.

```php
'skins' => [
    'allowed' => ['list', 'gallery', 'custom', 'blog', 'my-skin'],
    'default' => 'list',
    'path' => resource_path('views/bbs/skins'),
],
```

각 스킨은 `{skin}/list.blade.php`, `{skin}/show.blade.php`, `{skin}/form.blade.php` 구조를 사용합니다.

## 글쓰기 에디터

기본 글쓰기 에디터는 무료 Trix 에디터입니다. 패키지는 `trix`, `quill`, `tinymce`, `textarea`를 기본 제공합니다. 단순 textarea로 되돌리거나 스킨별로 다른 에디터를 쓰고 싶다면 `config/korean-bbs.php`의 `editors`를 수정합니다.

```php
'editors' => [
    'default' => 'trix',
    'allow_source_view' => true,
    'skins' => [
        'gallery' => 'quill',
        'blog' => 'tinymce',
        'custom' => 'my-editor',
    ],
    'path' => resource_path('views/vendor/korean-bbs/editors'),
],
```

기본 에디터 키:

| 키 | 설명 |
|----|------|
| `trix` | 기본값. 가볍고 게시판 글쓰기에 적합 |
| `quill` | 무료 BSD 라이선스 기반 에디터. 툴바 커스터마이징이 쉬움 |
| `tinymce` | GPL self-host 방식으로 로드하는 고기능 에디터 |
| `textarea` | 자바스크립트 에디터 없이 순수 textarea 사용 |

커스텀 에디터는 `php artisan vendor:publish --tag=korean-bbs-editors`로 기본 에디터 뷰를 복사한 뒤, `resources/views/vendor/korean-bbs/editors/my-editor.blade.php`처럼 추가합니다. 에디터 Blade는 Livewire의 `content` 속성과 동기화되면 됩니다.

## CAPTCHA

글 작성·수정 폼에 자동등록 방지(CAPTCHA)를 적용할 수 있습니다. 설정은 `config/korean-bbs.php`의 `captcha` 항목에서 합니다.

```php
'captcha' => [
    'enabled' => true,
    'guest_only' => true,
    'provider' => env('BBS_CAPTCHA_PROVIDER', 'math'),
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
```

| 항목 | 설명 |
|------|------|
| `enabled` | CAPTCHA 사용 여부 |
| `guest_only` | `true`이면 비회원(패키지 관리자 세션 제외)에게만 적용 |
| `provider` | `math`, `turnstile`, `recaptcha` 중 선택 |
| `min`, `max` | `math` 제공자에서 덧셈 문제에 쓰는 숫자 범위 |

제공자:

| 키 | 설명 |
|----|------|
| `math` | 기본값. 외부 서비스 없이 덧셈 문제를 표시 |
| `turnstile` | [Cloudflare Turnstile](https://developers.cloudflare.com/turnstile/) |
| `recaptcha` | [Google reCAPTCHA](https://www.google.com/recaptcha/) |

`turnstile` 또는 `recaptcha`를 지정했는데 site key·secret key가 비어 있으면 자동으로 `math`로 대체됩니다.

### 환경 변수

외부 CAPTCHA를 쓸 때 `.env`에 키를 설정합니다.

```env
# math | turnstile | recaptcha
BBS_CAPTCHA_PROVIDER=math

# Cloudflare Turnstile
BBS_TURNSTILE_SITE_KEY=
BBS_TURNSTILE_SECRET_KEY=

# Google reCAPTCHA
BBS_RECAPTCHA_SITE_KEY=
BBS_RECAPTCHA_SECRET_KEY=
```

CAPTCHA UI는 `resources/views/components/captcha.blade.php`에 있습니다. 스킨 폼에서 `@include('korean-bbs::components.captcha')`로 포함됩니다. 커스터마이징하려면 `php artisan vendor:publish --tag=korean-bbs-views` 후 해당 Blade를 수정하세요.

## 레이아웃 props

공개 게시판 화면은 layout에 다음 데이터를 전달합니다. `config('korean-bbs.layout')`을 앱 레이아웃으로 바꾼 경우에도 동일한 변수를 사용할 수 있습니다.

| 변수 | 설명 |
|------|------|
| `$title` | 현재 페이지 제목 |
| `$breadcrumbs` | Breadcrumb 항목 배열 (`label`, 선택 `url`) |
| `$board` | 현재 게시판 모델. 게시판 목록 화면에서는 없음 |
| `$post` | 현재 게시글 모델. 상세/수정 화면에서만 전달 |

예시:

```blade
@isset($breadcrumbs)
    <nav>
        @foreach($breadcrumbs as $breadcrumb)
            @if(!empty($breadcrumb['url']))
                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
            @else
                <span>{{ $breadcrumb['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endisset
```

## 기술 스택

- Laravel 11+
- Livewire 3
- TailwindCSS (CDN)
- Alpine.js (Livewire 번들)

## 라이선스

MIT
