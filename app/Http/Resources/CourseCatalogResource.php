<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseCatalogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'cover_image_path' => $this->coverImageUrl(),
            'promo_video_url' => $this->promo_video_url,
            'status' => $this->status,
            'duration_minutes' => $this->duration_minutes,
        ];
    }
}
