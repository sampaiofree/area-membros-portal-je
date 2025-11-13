<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FinalTest;
use App\Models\FinalTestQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinalTestQuestionController extends Controller
{
    public function index(Course $course, FinalTest $finalTest): View
    {
        $this->authorizeTest($course, $finalTest);

        $finalTest->load(['questions.options']);

        return view('final-tests.questions.index', compact('course', 'finalTest'));
    }

    public function create(Course $course, FinalTest $finalTest): View
    {
        $this->authorizeTest($course, $finalTest);

        $question = new FinalTestQuestion([
            'position' => ($finalTest->questions()->max('position') ?? 0) + 1,
            'weight' => 1,
        ]);

        return view('final-tests.questions.create', compact('course', 'finalTest', 'question'));
    }

    public function store(Request $request, Course $course, FinalTest $finalTest): RedirectResponse
    {
        $this->authorizeTest($course, $finalTest);

        $validated = $this->validateQuestion($request);

        $question = $finalTest->questions()->create($validated);

        return redirect()
            ->route('courses.final-test.questions.edit', [$course, $finalTest, $question])
            ->with('status', 'Questão criada. Adicione as alternativas.');
    }

    public function edit(Course $course, FinalTest $finalTest, FinalTestQuestion $question): View
    {
        $this->authorizeTest($course, $finalTest, $question);

        $question->load('options');

        return view('final-tests.questions.edit', compact('course', 'finalTest', 'question'));
    }

    public function update(Request $request, Course $course, FinalTest $finalTest, FinalTestQuestion $question): RedirectResponse
    {
        $this->authorizeTest($course, $finalTest, $question);

        $question->update($this->validateQuestion($request));

        return back()->with('status', 'Questão atualizada.');
    }

    public function destroy(Course $course, FinalTest $finalTest, FinalTestQuestion $question): RedirectResponse
    {
        $this->authorizeTest($course, $finalTest, $question);

        $question->delete();

        return redirect()
            ->route('courses.final-test.questions.index', [$course, $finalTest])
            ->with('status', 'Questão removida.');
    }

    private function authorizeTest(Course $course, FinalTest $finalTest, ?FinalTestQuestion $question = null): void
    {
        abort_if($finalTest->course_id !== $course->id, 404);
        if ($question) {
            abort_if($question->final_test_id !== $finalTest->id, 404);
        }
    }

    private function validateQuestion(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'statement' => ['nullable', 'string'],
            'position' => ['required', 'integer', 'min:1'],
            'weight' => ['required', 'integer', 'min:1', 'max:10'],
        ]);
    }
}
