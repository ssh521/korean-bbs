<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

$prefix = config('korean-bbs.prefix.web', 'bbs');

Route::prefix($prefix)->name('bbs.')->group(function () {
    // BBS 메인 (게시판 그룹 목록)
    Route::get('/', \Ssh521\KoreanBbs\Http\Livewire\Board\BoardIndex::class)->name('index');

    // 게시글 목록
    Route::get('/{boardSlug}', \Ssh521\KoreanBbs\Http\Livewire\Board\PostList::class)->name('posts.index');

    // 게시글 작성
    Route::get('/{boardSlug}/create', \Ssh521\KoreanBbs\Http\Livewire\Board\PostForm::class)->name('posts.create');

    // 게시글 상세
    Route::get('/{boardSlug}/{post}', \Ssh521\KoreanBbs\Http\Livewire\Board\PostShow::class)->name('posts.show');

    // 게시글 수정
    Route::get('/{boardSlug}/{post}/edit', \Ssh521\KoreanBbs\Http\Livewire\Board\PostForm::class)->name('posts.edit');

    // 파일 다운로드
    Route::get('/file/{file}/download', [\Ssh521\KoreanBbs\Http\Controllers\FileController::class, 'download'])->name('file.download');
});
