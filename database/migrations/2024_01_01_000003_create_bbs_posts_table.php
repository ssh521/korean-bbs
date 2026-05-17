<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bbs_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('bbs_boards')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name')->nullable();
            $table->string('author_password')->nullable();
            $table->string('title');
            $table->longText('content');
            $table->boolean('is_notice')->default(false);
            $table->boolean('is_secret')->default(false);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('dislike_count')->default(0);
            $table->string('thumbnail_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['board_id', 'is_notice', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bbs_posts');
    }
};
