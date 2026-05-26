<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bbs_boards', function (Blueprint $table) {
            $table->unsignedTinyInteger('list_level')->default(0)->after('width');
            $table->unsignedTinyInteger('read_level')->default(0)->after('list_level');
            $table->unsignedTinyInteger('upload_level')->default(0)->after('file_level');
            $table->unsignedTinyInteger('download_level')->default(0)->after('upload_level');
            $table->unsignedTinyInteger('like_level')->default(0)->after('download_level');
        });

        DB::table('bbs_boards')->update([
            'upload_level' => DB::raw('file_level'),
        ]);
    }

    public function down(): void
    {
        Schema::table('bbs_boards', function (Blueprint $table) {
            $table->dropColumn([
                'list_level',
                'read_level',
                'upload_level',
                'download_level',
                'like_level',
            ]);
        });
    }
};
