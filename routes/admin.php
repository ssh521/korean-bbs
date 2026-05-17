<?php

use Illuminate\Support\Facades\Route;
use Ssh521\KoreanBbs\Http\Controllers\AdminAuthController;
use Ssh521\KoreanBbs\Http\Middleware\AdminAuthMiddleware;

$prefix = config('korean-bbs.prefix.admin', 'bbs-admin');

Route::prefix($prefix)->name('bbs.admin.')->group(function () {
    // 관리자 로그인
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // 인증이 필요한 관리자 라우트
    Route::middleware(AdminAuthMiddleware::class)->group(function () {
        Route::get('/', \Ssh521\KoreanBbs\Http\Livewire\Admin\Dashboard::class)->name('dashboard');

        // 게시판 관리
        Route::get('/boards', \Ssh521\KoreanBbs\Http\Livewire\Admin\BoardManager::class)->name('boards.index');
        Route::get('/boards/create', \Ssh521\KoreanBbs\Http\Livewire\Admin\BoardForm::class)->name('boards.create');
        Route::get('/boards/{board}/edit', \Ssh521\KoreanBbs\Http\Livewire\Admin\BoardForm::class)->name('boards.edit');

        // 게시글 관리
        Route::get('/posts', \Ssh521\KoreanBbs\Http\Livewire\Admin\PostManager::class)->name('posts.index');

        // 댓글 관리
        Route::get('/comments', \Ssh521\KoreanBbs\Http\Livewire\Admin\CommentManager::class)->name('comments.index');
    });
});
