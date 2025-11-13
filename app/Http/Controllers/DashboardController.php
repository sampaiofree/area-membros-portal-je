<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isStudent()) {
            $enrollments = $user->enrollments()
                ->with([
                    'course.owner',
                    'course.modules.lessons' => fn ($query) => $query->orderBy('position'),
                    'course.finalTest.questions',
                ])
                ->orderByDesc('updated_at')
                ->get();

            return view('dashboard.student', [
                'user' => $user,
                'enrollments' => $enrollments,
            ]);
        }

        $courseQuery = Course::query()
            ->withCount(['modules', 'lessons'])
            ->with(['finalTest', 'modules.lessons']);

        if ($user->isTeacher()) {
            $courseQuery->where('owner_id', $user->id);
        }

        $courses = $courseQuery->orderBy('title')->get();

        $stats = [
            'courses' => $courses->count(),
            'modules' => $courses->sum(fn ($course) => (int) ($course->modules_count ?? 0)),
            'lessons' => $courses->sum(fn ($course) => (int) ($course->lessons_count ?? 0)),
            'final_tests' => $courses->whereNotNull('finalTest')->count(),
        ];

        return view('dashboard.index', compact('user', 'courses', 'stats'));
    }
}
