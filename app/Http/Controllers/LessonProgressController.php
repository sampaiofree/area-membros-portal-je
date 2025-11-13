<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Support\EnsuresStudentEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{
    use EnsuresStudentEnrollment;

    public function store(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $user = $request->user();
        $enrollment = $this->ensureEnrollment($user, $course);

        abort_if($lesson->module->course_id !== $course->id, 404);

        LessonCompletion::updateOrCreate(
            [
                'lesson_id' => $lesson->id,
                'user_id' => $user->id,
            ],
            [
                'completed_at' => now(),
            ]
        );

        $enrollment->recalculateProgress();

        $nextLesson = $course->nextLessonFor($user);

        if (! $nextLesson) {
            return redirect()->route('learning.courses.lessons.show', [$course, $lesson])
                ->with('status', 'Parabéns! Todas as aulas foram concluídas. Solicite seu certificado.');
        }

        return redirect()->route('learning.courses.lessons.show', [$course, $nextLesson])
            ->with('status', 'Aula concluída. Próxima aula liberada.');
    }
}
