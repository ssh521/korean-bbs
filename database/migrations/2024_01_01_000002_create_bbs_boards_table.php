<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bbs_boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->nullable()->constrained('bbs_groups')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['list', 'gallery'])->default('list');
            // 0=비회원, 1=회원 필요
            $table->unsignedTinyInteger('write_level')->default(0);
            $table->unsignedTinyInteger('comment_level')->default(0);
            $table->unsignedTinyInteger('file_level')->default(0);
            $table->unsignedSmallInteger('posts_per_page')->default(20);
            $table->boolean('allow_secret')->default(false);
            $table->boolean('use_comment')->default(true);
            $table->boolean('use_like')->default(true);
            $table->boolean('use_file')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bbs_boards');
    }
};
