<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }} - 커뮤니티</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800">

<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('bbs.index') }}" class="text-xl font-bold text-blue-600 hover:text-blue-700">
            {{ config('app.name', '커뮤니티') }}
        </a>
        <nav class="flex items-center gap-4 text-sm">
            @auth
                <span class="text-gray-600">{{ auth()->user()->name }}님</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-gray-700">로그아웃</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">로그인</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">회원가입</a>
            @endauth
            <a href="{{ route('bbs.admin.login') }}" class="text-xs text-gray-400 hover:text-gray-600">관리자</a>
        </nav>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-6">
    {{ $slot }}
</main>

<footer class="mt-16 border-t border-gray-200 bg-white">
    <div class="max-w-6xl mx-auto px-4 py-6 text-center text-sm text-gray-400">
        Powered by <span class="font-medium">Korean BBS</span>
    </div>
</footer>

@livewireScripts
</body>
</html>
