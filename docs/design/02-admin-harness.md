# Korean BBS Admin Design Harness

## 1) 목적
- 관리자 화면의 디자인/동작 불일치를 방지한다.
- 데이터 탐색, 필터링, 수정, 삭제, 상태 파악 속도를 높인다.

## 2) 관리자 정책
1. 관리자 화면은 스킨 비적용 영역이다.
2. 색상 커스터마이징은 토큰 값 변경으로만 수행한다.
3. 페이지별 임의 스타일 생성 대신 공통 컴포넌트를 우선 사용한다.

## 3) 기본 레이아웃
1. Sidebar
2. Topbar/Flash Area
3. Page Header (제목/설명/Primary Action)
4. Filter/Search Panel
5. Data Table (Desktop)
6. Card List (Mobile)
7. Pagination
8. Empty/Loading/Error State

## 4) List Page 필수 요소
1. 제목 + 설명
2. Primary Action 버튼
3. 검색 입력
4. 필터 그룹
5. 정렬 옵션(필요 시)
6. 선택 개수 표시
7. Bulk Action
8. 데이터 목록
9. Row Action
10. 페이지네이션
11. Empty/Loading/Error 상태

## 5) 반응형 규칙
1. 데스크톱(`md` 이상): 테이블 중심
2. 모바일(`md` 미만): 카드 리스트로 전환
3. 모바일에서 "테이블 축소"만으로 대응하지 않는다.

## 6) 컴포넌트 권장 구조
`resources/views/components/admin/`에 아래 컴포넌트 정의를 권장한다.

1. `button.blade.php`
2. `input.blade.php`
3. `select.blade.php`
4. `badge.blade.php`
5. `table.blade.php`
6. `filter-panel.blade.php`
7. `list-shell.blade.php`
8. `state-empty.blade.php`
9. `state-loading.blade.php`
10. `state-error.blade.php`

## 7) 관리자 액션 버튼 규칙
1. Primary 버튼은 화면당 1개를 기본으로 한다.
2. Danger 액션은 확인 메시지와 함께 제공한다.
3. Bulk Action은 선택 수량이 1개 이상일 때만 활성화한다.

## 8) 접근성 기본 규칙
1. 입력 필드에는 label 또는 접근 가능한 대체 라벨을 제공한다.
2. 모든 버튼/링크는 키보드 포커스 스타일을 제공한다.
3. 테이블 헤더는 의미 태그(`th`)를 사용한다.
4. 색상만으로 상태를 구분하지 않고 텍스트를 함께 제공한다.

## 9) 상태 화면 규칙
1. Empty: 다음 행동 버튼을 포함한다.
2. Loading: 2~5개 스켈레톤 행을 표시한다.
3. Error: 실패 원인 안내 + 재시도 유도 문구를 포함한다.

## 10) 구현 우선순위
1. 공통 컴포넌트 정의
2. `boards/posts/comments` 리스트 페이지 공통 껍데기 통일
3. 모바일 카드 전환 통일
4. 세부 페이지(폼/상세) 확장
