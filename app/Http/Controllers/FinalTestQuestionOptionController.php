<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FinalTest;
use App\Models\FinalTestQuestion;
use App\Models\FinalTestQuestionOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinalTestQuestionOptionController extends Controller
{
    public function create(Course $course, FinalTest $finalTest, FinalTestQuestion $question): View
    {
        $this->authorizeResources($course, $finalTest, $question);

        $option = new FinalTestQuestionOption([
            'position' => ($question->options()->max('position') ?? 0) + 1,
        ]);

        return view('final-tests.options.create', compact('course', 'finalTest', 'question', 'option'));
    }

    public function store(Request $request, Course $course, FinalTest $finalTest, FinalTestQuestion $question): RedirectResponse
    {
        $this->authorizeResources($course, $finalTest, $question);

        $option = $question->options()->create($this->validateOption($request));
        $this->syncCorrectOption($question, $option);

        return redirect()
            ->route('courses.final-test.questions.edit', [$course, $finalTest, $question])
            ->with('status', 'Opção adicionada.');
    }

    public function edit(Course $course, FinalTest $finalTest, FinalTestQuestion $question, FinalTestQuestionOption $option): View
    {
        $this->authorizeResources($course, $finalTest, $question, $option);

        return view('final-tests.options.edit', compact('course', 'finalTest', 'question', 'option'));
    }

    public function update(Request $request, Course $course, FinalTest $finalTest, FinalTestQuestion $question, FinalTestQuestionOption $option): RedirectResponse
    {
        $this->authorizeResources($course, $finalTest, $question, $option);

        $option->update($this->validateOption($request));

        $this->syncCorrectOption($question, $option);

        if (! $question->options()->where('is_correct', true)->exists()) {
            $option->update(['is_correct' => true]);
        }

        return redirect()
            ->route('courses.final-test.questions.edit', [$course, $finalTest, $question])
            ->with('status', 'Opção atualizada.');
    }

    public function destroy(Course $course, FinalTest $finalTest, FinalTestQuestion $question, FinalTestQuestionOption $option): RedirectResponse
    {
        $this->authorizeResources($course, $finalTest, $question, $option);

        $option->delete();

        return redirect()
            ->route('courses.final-test.questions.edit', [$course, $finalTest, $question])
            ->with('status', 'Opção removida.');
    }

    private function validateOption(Request $request): array
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'is_correct' => ['nullable', 'boolean'],
            'position' => ['required', 'integer', 'min:1'],
        ]);

        $data['is_correct'] = $request->boolean('is_correct');

        return $data;
    }

    private function authorizeResources(
        Course $course,
        FinalTest $finalTest,
        FinalTestQuestion $question,
        ?FinalTestQuestionOption $option = null
    ): void {
        abort_if($finalTest->course_id !== $course->id, 404);
        abort_if($question->final_test_id !== $finalTest->id, 404);

        if ($option) {
            abort_if($option->final_test_question_id !== $question->id, 404);
        }
    }

    private function syncCorrectOption(FinalTestQuestion $question, FinalTestQuestionOption $option): void
    {
        if ($option->is_correct) {
            $question->options()
                ->whereKeyNot($option->id)
                ->update(['is_correct' => false]);
        }
    }
}
