<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $columns = function (Blueprint $table) {
            $table->longText('html')->nullable();
            $table->longText('plain_text')->nullable();
            $table->integer('word_count')->default(0);
            $table->integer('reading_time')->default(1);
        };

        Schema::table('posts', $columns);
        Schema::table('projects', $columns);
        Schema::table('users', $columns);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = function (Blueprint $table) {
            $table->dropColumn(['html', 'plain_text', 'word_count', 'reading_time']);
        };

        Schema::table('posts', $columns);
        Schema::table('projects', $columns);
        Schema::table('users', $columns);
    }
};
