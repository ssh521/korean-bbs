<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 로그인 - BBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">BBS 관리자</h1>
                <p class="text-sm text-gray-500 mt-1">관리자 계정으로 로그인하세요</p>
            </div>

            <form method="POST" action="{{ route('bbs.admin.login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="id" class="block text-sm font-medium text-gray-700 mb-1">아이디</label>
                    <input type="text" id="id" name="id" value="{{ old('id') }}" required autofocus
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id') border-red-400 @enderror">
                    @error('id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">비밀번호</label>
                    <input type="password" id="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg text-sm transition mt-2">
                    로그인
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('bbs.index') }}" class="text-xs text-gray-400 hover:text-gray-600">
                    ← 사이트로 돌아가기
                </a>
            </div>
        </div>
    </div>
</body>
</html>
