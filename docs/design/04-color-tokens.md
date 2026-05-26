# Korean BBS Color Tokens

## 1) 목적
- 관리자/기본 UI 색상 일관성을 유지한다.
- 관리자 영역은 "색상 토큰 값 변경"만 허용한다.

## 2) 고정 브랜드 팔레트
1. Primary: `#2563EB` (Tailwind `blue-600`)
2. Primary Hover: `#1D4ED8` (`blue-700`)
3. Success: `#059669` (`emerald-600`)
4. Warning: `#D97706` (`amber-600`)
5. Danger: `#DC2626` (`red-600`)

## 3) 공통 중립 팔레트
1. Background: `#F8FAFC` (`slate-50`)
2. Surface: `#FFFFFF` (`white`)
3. Text Default: `#0F172A` (`slate-900`)
4. Text Muted: `#475569` (`slate-600`)
5. Border: `#E2E8F0` (`slate-200`)

## 4) CSS 토큰 명세
```css
:root {
  --kbbs-color-primary: #2563EB;
  --kbbs-color-primary-hover: #1D4ED8;
  --kbbs-color-success: #059669;
  --kbbs-color-warning: #D97706;
  --kbbs-color-danger: #DC2626;

  --kbbs-color-bg: #F8FAFC;
  --kbbs-color-surface: #FFFFFF;
  --kbbs-color-text: #0F172A;
  --kbbs-color-text-muted: #475569;
  --kbbs-color-border: #E2E8F0;
}
```

## 5) 관리자 색상 커스터마이징 규칙
1. 허용: 위 토큰 값 재정의
2. 비허용: 컴포넌트별 임의 hex 직접 하드코딩
3. 비허용: 페이지 단위 서로 다른 primary 색 사용

## 6) 상태 색상 매핑 규칙
1. 활성/성공: Success
2. 경고/주의: Warning
3. 삭제/치명 오류: Danger
4. 링크/주요 액션: Primary

## 7) 대비 권장
1. 버튼 텍스트 대비를 우선 확인한다.
2. 상태 배지는 배경+텍스트 조합으로 충분한 대비를 유지한다.
