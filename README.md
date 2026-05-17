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
| `skins` | 허용 스킨 키 등 (`list`, `gallery`, `custom` 등) |

## 뷰 커스터마이징

```bash
php artisan vendor:publish --tag=korean-bbs-views
```

Blade는 `resources/views/vendor/korean-bbs/`에 복사됩니다.

## 기술 스택

- Laravel 11+
- Livewire 3
- TailwindCSS (CDN)
- Alpine.js (Livewire 번들)

## 라이선스

MIT
