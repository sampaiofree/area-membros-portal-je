<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\View\View;

class PublicCoursePageController extends Controller
{
    public function __invoke(Course $course): View
    {
        abort_if($course->status !== 'published', 404);

        $course->loadMissing([
            'modules.lessons' => fn ($query) => $query->orderBy('position'),
            'certificateBranding',
            'enrollments',
        ]);

        return view('courses.public', [
            'course' => $course,
            'studentCount' => $course->enrollments->count(),
        ]);
    }
}
