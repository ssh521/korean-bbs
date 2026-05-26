<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? '관리자' }} - BBS 관리</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen">
    <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-gray-700 bg-gray-900 text-white lg:flex">
        <div class="border-b border-gray-700 px-6 py-5">
            <h1 class="text-lg font-bold text-white">BBS 관리자</h1>
            <p class="mt-1 text-xs text-gray-400">{{ session('bbs_admin_name', '관리자') }}</p>
        </div>

        <nav class="flex-1 space-y-1 px-4 py-6">
            <a href="{{ route('bbs.admin.dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs('bbs.admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                대시보드
            </a>
            <a href="{{ route('bbs.admin.boards.index') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs('bbs.admin.boards.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                게시판 관리
            </a>
            <a href="{{ route('bbs.admin.posts.index') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs('bbs.admin.posts.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                게시글 관리
            </a>
            <a href="{{ route('bbs.admin.comments.index') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs('bbs.admin.comments.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                댓글 관리
            </a>
        </nav>

        <div class="border-t border-gray-700 px-4 py-4">
            <a href="{{ route('bbs.index') }}" class="mb-3 flex items-center gap-2 text-xs text-gray-400 hover:text-white">
                사이트 보기
            </a>
            <form method="POST" action="{{ route('bbs.admin.logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-xs text-gray-400 transition hover:text-red-400">
                    로그아웃
                </button>
            </form>
        </div>
    </aside>

    <div class="flex min-h-screen flex-col lg:pl-64">
        <header class="border-b border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-4 py-3 lg:hidden">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-900">BBS 관리자</p>
                    <form method="POST" action="{{ route('bbs.admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-gray-500 hover:text-red-500">로그아웃</button>
                    </form>
                </div>
                <nav class="mt-3 flex gap-2 overflow-x-auto pb-1 text-xs">
                    <a href="{{ route('bbs.admin.dashboard') }}"
                       class="whitespace-nowrap rounded-full px-3 py-1.5 {{ request()->routeIs('bbs.admin.dashboard') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                        대시보드
                    </a>
                    <a href="{{ route('bbs.admin.boards.index') }}"
                       class="whitespace-nowrap rounded-full px-3 py-1.5 {{ request()->routeIs('bbs.admin.boards.*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                        게시판
                    </a>
                    <a href="{{ route('bbs.admin.posts.index') }}"
                       class="whitespace-nowrap rounded-full px-3 py-1.5 {{ request()->routeIs('bbs.admin.posts.*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                        게시글
                    </a>
                    <a href="{{ route('bbs.admin.comments.index') }}"
                       class="whitespace-nowrap rounded-full px-3 py-1.5 {{ request()->routeIs('bbs.admin.comments.*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                        댓글
                    </a>
                </nav>
            </div>

            <div class="px-4 py-4 sm:px-6">
                @if(session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
