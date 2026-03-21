<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;

/** @mixin \App\Models\User */
class PublicUserResource extends PublicResource
{
    /**
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

            'avatar' => $this->profile_photo_url,
            'cover' => $this->cover_photo_url,

            'social' => $this->social_links,

            'projects' => PublicProjectResource::collection(
                $this->whenLoaded('projects')
            ),

            'posts' => PublicPostResource::collection(
                $this->whenLoaded('posts')
            ),

            // 'seo' => $this->seo(),
        ];
    }
}
