<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserPublicResource extends JsonResource
{
    /**
     * @property-read Collection<Post> $posts
     * @property-read Collection<Project> $projects
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'title' => $this->title,
            'bio' => $this->bio,
            'location' => $this->location,
            'social_links' => $this->social_links ?? [],
            'profile_photo_url' => $this->profile_photo_url,
            'cover_photo_url' => $this->cover_photo_url,

            'posts' => $this->whenLoaded('posts', fn () => $this->posts->map(fn ($post) => [
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'published_at' => $post->published_at,
                'body' => tiptap_converter()->asHTML($post->getRawOriginal('body')),
                'category' => $post->relationLoaded('category') && $post->category
                    ? ['name' => $post->category->name]
                    : null,
            ])),

            'projects' => $this->whenLoaded('projects', fn () => $this->projects->map(fn ($project) => [
                'title' => $project->title,
                'slug' => $project->slug,
                'description' => $project->description,
                'content' => tiptap_converter()->asHTML($project->getRawOriginal('content')),
                'client' => $project->client,
                'year' => $project->year,
                'stack' => $project->stack,
                'url' => $project->url,
                'featured' => $project->featured,
                'cover_url' => $project->getFirstMediaUrl('cover'),
            ])),
        ];
    }
}
