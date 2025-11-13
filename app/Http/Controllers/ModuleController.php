<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Support\HandlesCourseAuthorization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuleController extends Controller
{
    use HandlesCourseAuthorization;

    public function create(Request $request, Course $course): View
    {
        $this->ensureCanManageCourse($request->user(), $course);

        $module = new Module([
            'position' => ($course->modules()->max('position') ?? 0) + 1,
        ]);

        return view('modules.create', compact('course', 'module'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->ensureCanManageCourse($request->user(), $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        $nextPosition = ($course->modules()->max('position') ?? 0) + 1;

        $course->modules()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'position' => $validated['position'] ?? $nextPosition,
        ]);

        return redirect()->route('courses.edit', $course)->with('status', 'Módulo criado.');
    }

    public function edit(Request $request, Module $module): View
    {
        $course = $module->course;
        $this->ensureCanManageCourse($request->user(), $course);

        return view('modules.edit', compact('module', 'course'));
    }

    public function update(Request $request, Module $module): RedirectResponse
    {
        $course = $module->course;
        $this->ensureCanManageCourse($request->user(), $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        $module->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'position' => $validated['position'] ?? $module->position,
        ]);

        return redirect()->route('courses.edit', $course)->with('status', 'Módulo atualizado.');
    }

    public function destroy(Request $request, Module $module): RedirectResponse
    {
        $course = $module->course;
        $this->ensureCanManageCourse($request->user(), $course);

        $module->delete();

        return redirect()->route('courses.edit', $course)->with('status', 'Módulo removido.');
    }
}
