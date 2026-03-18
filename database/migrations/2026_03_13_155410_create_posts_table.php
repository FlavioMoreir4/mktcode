<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title');
            $blueprint->string('slug')->unique();
            $blueprint->longText('body')->nullable();
            $blueprint->text('excerpt')->nullable();
            $blueprint->string('status')->default('draft'); // draft, published
            $blueprint->dateTime('published_at')->nullable();
            $blueprint->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $blueprint->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $blueprint->string('seo_title')->nullable();
            $blueprint->text('seo_description')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
