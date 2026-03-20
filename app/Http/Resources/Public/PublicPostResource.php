<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

/** @mixin Post
 * @property-read User $author
 * @property-read Category $category
 */
class PublicPostResource extends PublicResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt ?? $this->plain_text,
            'published_at' => optional($this->published_at)->toIso8601String(),

            /*
            |--------------------------------------------------------------------------
            | Métricas do conteúdo
            |--------------------------------------------------------------------------
            */
            'word_count' => $this->word_count,
            'reading_time' => $this->reading_time,

            /*
            |--------------------------------------------------------------------------
            | Autor
            |--------------------------------------------------------------------------
            */
            'author' => $this->whenLoaded('author', function () {
                return [
                    'name' => $this->author->name,
                    'username' => $this->author->username,
                    'avatar' => $this->author?->profile_photo_url,
                ];
            }),

            /*
            |--------------------------------------------------------------------------
            | Categoria
            |--------------------------------------------------------------------------
            */
            'category' => $this->whenLoaded('category', function () {
                return [
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),

            /*
            |--------------------------------------------------------------------------
            | Tags
            |--------------------------------------------------------------------------
            */
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->map(fn (Tag $tag) => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ]);
            }),

            /*
            |--------------------------------------------------------------------------
            | Media (imagem principal)
            |--------------------------------------------------------------------------
            */
            'cover' => $this->cover(),

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */
            'seo' => [
                ...$this->seo($request),
                'type' => 'article',
            ],
        ];
    }
}
