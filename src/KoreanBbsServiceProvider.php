<?php

namespace Ssh521\KoreanBbs;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Ssh521\KoreanBbs\Http\Livewire\Admin\BoardForm;
use Ssh521\KoreanBbs\Http\Livewire\Admin\BoardManager;
use Ssh521\KoreanBbs\Http\Livewire\Admin\CommentManager;
use Ssh521\KoreanBbs\Http\Livewire\Admin\Dashboard;
use Ssh521\KoreanBbs\Http\Livewire\Admin\PostManager;
use Ssh521\KoreanBbs\Http\Livewire\Board\BoardIndex;
use Ssh521\KoreanBbs\Http\Livewire\Board\PostForm;
use Ssh521\KoreanBbs\Http\Livewire\Board\PostList;
use Ssh521\KoreanBbs\Http\Livewire\Board\PostShow;

class KoreanBbsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/korean-bbs.php',
            'korean-bbs'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'korean-bbs');

        $this->registerRoutes();

        $this->registerMiddleware();
        $this->registerLivewireComponents();
        $this->registerPublishables();
    }

    private function registerRoutes(): void
    {
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        });
    }

    private function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware(
            'bbs.admin',
            \Ssh521\KoreanBbs\Http\Middleware\AdminAuthMiddleware::class
        );
    }

    private function registerLivewireComponents(): void
    {
        Livewire::component('korean-bbs::board-index', BoardIndex::class);
        Livewire::component('korean-bbs::post-list', PostList::class);
        Livewire::component('korean-bbs::post-show', PostShow::class);
        Livewire::component('korean-bbs::post-form', PostForm::class);

        Livewire::component('korean-bbs::admin.dashboard', Dashboard::class);
        Livewire::component('korean-bbs::admin.board-manager', BoardManager::class);
        Livewire::component('korean-bbs::admin.board-form', BoardForm::class);
        Livewire::component('korean-bbs::admin.post-manager', PostManager::class);
        Livewire::component('korean-bbs::admin.comment-manager', CommentManager::class);
    }

    private function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/../config/korean-bbs.php' => config_path('korean-bbs.php'),
        ], 'korean-bbs-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'korean-bbs-migrations');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/korean-bbs'),
        ], 'korean-bbs-views');
    }
}
