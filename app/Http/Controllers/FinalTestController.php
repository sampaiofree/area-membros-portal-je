<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FinalTest;
use App\Support\HandlesCourseAuthorization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinalTestController extends Controller
{
    use HandlesCourseAuthorization;

    public function create(Request $request, Course $course): RedirectResponse|View
    {
        $this->ensureCanManageCourse($request->user(), $course);

        if ($course->finalTest) {
            return redirect()->route('courses.final-test.edit', [$course, $course->finalTest]);
        }

        $finalTest = new FinalTest([
            'passing_score' => 70,
            'max_attempts' => 1,
        ]);

        return view('final-tests.create', compact('course', 'finalTest'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->ensureCanManageCourse($request->user(), $course);

        abort_if($course->finalTest, 400, 'O curso já possui um teste final.');

        $data = $this->validateData($request);

        $course->finalTest()->create($data);

        return redirect()->route('courses.edit', $course)->with('status', 'Teste final criado.');
    }

    public function edit(Request $request, Course $course, FinalTest $finalTest): View
    {
        $this->ensureCanManageCourse($request->user(), $course);
        $this->ensureBelongsToCourse($course, $finalTest);

        return view('final-tests.edit', compact('course', 'finalTest'));
    }

    public function update(Request $request, Course $course, FinalTest $finalTest): RedirectResponse
    {
        $this->ensureCanManageCourse($request->user(), $course);
        $this->ensureBelongsToCourse($course, $finalTest);

        $finalTest->update($this->validateData($request));

        return redirect()->route('courses.edit', $course)->with('status', 'Teste final atualizado.');
    }

    public function destroy(Request $request, Course $course, FinalTest $finalTest): RedirectResponse
    {
        $this->ensureCanManageCourse($request->user(), $course);
        $this->ensureBelongsToCourse($course, $finalTest);

        $finalTest->delete();

        return redirect()->route('courses.edit', $course)->with('status', 'Teste final removido.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string'],
            'passing_score' => ['required', 'integer', 'min:1', 'max:100'],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'duration_minutes' => ['nullable', 'integer', 'min:5'],
        ]);
    }

    private function ensureBelongsToCourse(Course $course, FinalTest $finalTest): void
    {
        abort_if($finalTest->course_id !== $course->id, 404);
    }
}
