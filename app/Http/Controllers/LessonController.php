<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Support\HandlesCourseAuthorization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    use HandlesCourseAuthorization;

    public function create(Request $request, Module $module): View
    {
        $course = $module->course;
        $this->ensureCanManageCourse($request->user(), $course);

        $lesson = new Lesson([
            'position' => ($module->lessons()->max('position') ?? 0) + 1,
        ]);

        return view('lessons.create', compact('lesson', 'module', 'course'));
    }

    public function store(Request $request, Module $module): RedirectResponse
    {
        $course = $module->course;
        $this->ensureCanManageCourse($request->user(), $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        $module->lessons()->create([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'position' => $validated['position'] ?? (($module->lessons()->max('position') ?? 0) + 1),
        ]);

        return redirect()->route('courses.edit', $course)->with('status', 'Aula criada.');
    }

    public function edit(Request $request, Lesson $lesson): View
    {
        $module = $lesson->module;
        $course = $module->course;
        $this->ensureCanManageCourse($request->user(), $course);

        return view('lessons.edit', compact('lesson', 'module', 'course'));
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $module = $lesson->module;
        $course = $module->course;
        $this->ensureCanManageCourse($request->user(), $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        $lesson->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'position' => $validated['position'] ?? $lesson->position,
        ]);

        return redirect()->route('courses.edit', $course)->with('status', 'Aula atualizada.');
    }

    public function destroy(Request $request, Lesson $lesson): RedirectResponse
    {
        $module = $lesson->module;
        $course = $module->course;
        $this->ensureCanManageCourse($request->user(), $course);

        $lesson->delete();

        return redirect()->route('courses.edit', $course)->with('status', 'Aula removida.');
    }
}
