<?php

declare(strict_types=1);

namespace App\Application\Portfolio\DTOs;

use App\Application\Shared\Contracts\PublicPayloadData;
use App\Application\Shared\DTOs\PublicAuthorData;
use App\Models\Project;

final readonly class PublicProjectViewData implements PublicPayloadData
{
    /**
     * @param  list<string>  $gallery
     * @param  list<string>|null  $stack
     */
    public function __construct(
        public string $title,
        public string $slug,
        public ?string $description,
        public mixed $content,
        public ?string $client,
        public ?int $year,
        public ?array $stack,
        public ?string $url,
        public bool $featured,
        public ?array $author,
        // public ?string $cover,
        public ?array $media,
        // public array $gallery,
    ) {}

    public static function fromModel(Project $project, ?array $media = null): self
    {
        return new self(
            title: $project->title,
            slug: $project->slug,
            description: $project->description,
            content: $project->content,
            client: $project->client,
            year: $project->year ? (int) $project->year : null,
            stack: $project->stack,
            url: $project->url,
            featured: (bool) $project->featured,
            author: $project->relationLoaded('author') && $project->author
                ? PublicAuthorData::summary($project->author)->toArray()
                : null,
            // cover: $project->getFirstMediaUrl('cover') ?: null,
            media: $media,
            // gallery: $project->getMedia('screenshots')->map->getUrl()->all(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'client' => $this->client,
            'year' => $this->year,
            'stack' => $this->stack,
            'url' => $this->url,
            'featured' => $this->featured,
            'author' => $this->author,
            // 'cover' => $this->cover,
            'media' => $this->media,
            // 'gallery' => $this->gallery,
        ];
    }
}
