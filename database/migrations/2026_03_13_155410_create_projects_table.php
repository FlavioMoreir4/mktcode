<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title');
            $blueprint->string('slug')->unique();
            $blueprint->text('description')->nullable();
            $blueprint->longText('content')->nullable();
            $blueprint->string('client')->nullable();
            $blueprint->string('year')->nullable();
            $blueprint->string('status')->default('draft'); // draft, published
            $blueprint->json('stack')->nullable();
            $blueprint->string('url')->nullable();
            $blueprint->boolean('featured')->default(false);
            $blueprint->integer('sort_order')->default(0);
            $blueprint->string('seo_title')->nullable();
            $blueprint->text('seo_description')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
