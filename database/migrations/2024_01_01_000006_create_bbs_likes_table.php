<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bbs_likes', function (Blueprint $table) {
            $table->id();
            $table->morphs('likeable'); // likeable_type, likeable_id
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->enum('type', ['like', 'dislike']);
            $table->timestamp('created_at')->useCurrent();

            // 동일 사용자/IP 중복 방지
            $table->unique(['likeable_type', 'likeable_id', 'user_id']);
            $table->index(['likeable_type', 'likeable_id', 'ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bbs_likes');
    }
};
