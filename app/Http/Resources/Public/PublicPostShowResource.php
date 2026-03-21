<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

/**
 * @mixin \App\Models\Post
 *
 * @property-read User $author
 * @property-read Category $category
 * @property-read Collection<int, Tag> $tags
 */
class PublicPostShowResource extends PublicResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->html,
            'markdown' => $this->markdown,
            'plain_text' => $this->plain_text,
            'excerpt' => $this->excerpt ?? $this->plain_text,
            'word_count' => $this->word_count,
            'reading_time' => $this->reading_time,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),

            'author' => $this->whenLoaded('author', fn (): array => [
                'name' => $this->author->name,
                'username' => $this->author->username,
                'avatar' => $this->author?->profile_photo_url,
                'location' => $this->author->location,
                'social_links' => $this->author->social_links,
            ]),

            'category' => $this->whenLoaded('category', fn (): array => [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),

            'tags' => $this->whenLoaded('tags', fn (): array => $this->tags->map(fn (Tag $tag): array => [
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])->all()
            ),

            'cover' => $this->cover(),
            'seo' => $this->seo(),
        ];
    }
}
