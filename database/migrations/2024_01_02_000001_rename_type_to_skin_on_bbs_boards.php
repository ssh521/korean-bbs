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
            $table->string('skin', 50)->default('list')->after('description');
        });

        DB::statement('UPDATE bbs_boards SET skin = type');

        Schema::table('bbs_boards', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    public function down(): void
    {
        Schema::table('bbs_boards', function (Blueprint $table) {
            $table->enum('type', ['list', 'gallery'])->default('list')->after('description');
        });

        DB::statement("UPDATE bbs_boards SET type = CASE WHEN skin = 'gallery' THEN 'gallery' ELSE 'list' END");

        Schema::table('bbs_boards', function (Blueprint $table) {
            $table->dropColumn('skin');
        });
    }
};
