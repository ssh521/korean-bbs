# Korean BBS (한국형 게시판)

Laravel + Livewire + TailwindCSS 기반의 한국형 BBS (게시판) 패키지입니다.
그누보드5, KBoard 스타일의 다기능 커뮤니티 게시판을 Laravel 프로젝트에 쉽게 추가할 수 있습니다.

## 주요 기능

- 다중 게시판 (그룹 지원)
- 게시판 타입: **일반형** (목록), **갤러리형** (썸네일 그리드)
- 게시글 CRUD, 공지글, 비밀글
- 댓글 & 대댓글
- 파일 첨부 (이미지 미리보기, 썸네일 자동 생성)
- 추천 / 비추천
- 비회원 글쓰기 지원
- Laravel 기본 Auth 연동
- 관리자 패널 (레이아웃 컴포넌트, config 기반 인증)

## 설치

### 1. 로컬 패키지 등록 (개발용)

`composer.json`에 추가:

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
composer update
```

### 2. 설정 파일 & 마이그레이션 퍼블리시

```bash
php artisan vendor:publish --tag=korean-bbs-config
php artisan migrate
php artisan storage:link
```

### 3. `.env` 설정

```env
BBS_ADMIN_ID=admin
BBS_ADMIN_PASSWORD=your_secure_password
BBS_ADMIN_NAME=관리자
```

## 접근 URL

| 경로 | 설명 |
|------|------|
| `/bbs` | BBS 메인 (게시판 목록) |
| `/bbs/{slug}` | 게시판 게시글 목록 |
| `/bbs/{slug}/{id}` | 게시글 상세 |
| `/bbs/{slug}/create` | 게시글 작성 |
| `/bbs-admin` | 관리자 대시보드 |
| `/bbs-admin/login` | 관리자 로그인 |

## 설정 (`config/korean-bbs.php`)

```php
'admin' => [
    'id'       => env('BBS_ADMIN_ID', 'admin'),
    'password' => env('BBS_ADMIN_PASSWORD', 'admin1234!'),
    'name'     => env('BBS_ADMIN_NAME', '관리자'),
],
'prefix' => [
    'web'   => 'bbs',       // BBS URL 프리픽스
    'admin' => 'bbs-admin', // 관리자 URL 프리픽스
],
'upload' => [
    'disk'     => 'public',
    'max_size' => 10240, // KB
],
```

## 뷰 커스터마이징

```bash
php artisan vendor:publish --tag=korean-bbs-views
```

`resources/views/vendor/korean-bbs/` 에 복사됩니다.

## 기술 스택

- Laravel 11+
- Livewire 3
- TailwindCSS (CDN)
- Alpine.js (Livewire 내장)
