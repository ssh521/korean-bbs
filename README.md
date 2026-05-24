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
- Laravel 기본 인증·패키지 관리자(config·세션)

## 설정 개요

설정 파일: `config/korean-bbs.php` (퍼블리시 후 앱에서 수정).

| 항목 | 설명 |
|------|------|
| `layout` | 공개 페이지 Blade 레이아웃 (예: `layouts.app`으로 교체) |
| `admin` | `.env`의 `BBS_ADMIN_*`와 연동되는 관리자 계정 |
| `prefix` | 웹·관리자 URL 접두사 |
| `upload` | 디스크, 경로, 최대 크기(KB), 허용 확장자, 썸네일 크기 |
| `defaults` | 페이지당 글·댓글·갤러리 개수 |
| `skins` | 허용 스킨 키, 기본 스킨, 커스텀 스킨 경로 |

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
    'allowed' => ['list', 'gallery', 'custom', 'my-skin'],
    'default' => 'list',
    'path' => resource_path('views/bbs/skins'),
],
```

각 스킨은 `{skin}/list.blade.php`, `{skin}/show.blade.php`, `{skin}/form.blade.php` 구조를 사용합니다.

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
