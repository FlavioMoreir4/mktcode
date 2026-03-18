<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Converter Posts
        Post::all()->each(function (Post $post) {
            if ($post->body && ! str_starts_with(mb_trim($post->body), '{')) {
                try {
                    $json = tiptap_converter()->asJSON($post->body);
                    $post->update(['body' => $json]);
                } catch (Exception $e) {
                    Log::error("Erro ao converter Post ID {$post->id}: ".$e->getMessage());
                }
            }
        });

        // Converter Projects
        Project::all()->each(function (Project $project) {
            if ($project->content && ! str_starts_with(mb_trim($project->content), '{')) {
                try {
                    $json = tiptap_converter()->asJSON($project->content);
                    $project->update(['content' => $json]);
                } catch (Exception $e) {
                    Log::error("Erro ao converter Project ID {$project->id}: ".$e->getMessage());
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Post::all()->each(function (Post $post) {
            if ($post->body && str_starts_with(mb_trim($post->body), '{')) {
                try {
                    $html = tiptap_converter()->asHTML($post->body);
                    $post->update(['body' => $html]);
                } catch (Exception $e) {
                    Log::error("Erro ao reverter Post ID {$post->id}: ".$e->getMessage());
                }
            }
        });

        Project::all()->each(function (Project $project) {
            if ($project->content && str_starts_with(mb_trim($project->content), '{')) {
                try {
                    $html = tiptap_converter()->asHTML($project->content);
                    $project->update(['content' => $html]);
                } catch (Exception $e) {
                    Log::error("Erro ao reverter Project ID {$project->id}: ".$e->getMessage());
                }
            }
        });
    }
};
