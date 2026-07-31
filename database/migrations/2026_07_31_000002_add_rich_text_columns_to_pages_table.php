<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->longText('html')->nullable();
            $table->longText('plain_text')->nullable();
            $table->integer('word_count')->default(0);
            $table->integer('reading_time')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn(['html', 'plain_text', 'word_count', 'reading_time']);
        });
    }
};
