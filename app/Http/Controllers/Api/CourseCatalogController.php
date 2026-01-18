<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCatalogResource;
use App\Models\Course;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseCatalogController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        $courses = Course::query()
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->get();

        return CourseCatalogResource::collection($courses);
    }
}
