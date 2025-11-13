<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\CertificateBranding;
use App\Models\Course;
use App\Models\User;
use App\Support\HandlesCourseAuthorization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    use HandlesCourseAuthorization;
    public function index(Request $request): View
    {
        $user = $request->user();

        $courses = Course::query()
            ->with(['owner', 'modules', 'finalTest'])
            ->when($user->isTeacher(), fn ($query) => $query->where('owner_id', $user->id))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('courses.index', compact('courses', 'user'));
    }

    public function create(Request $request): View
    {
        $teachers = $this->teachers();
        $user = $request->user();
        $course = new Course([
            'status' => 'draft',
            'owner_id' => $user->id,
        ]);

        return view('courses.create', compact('teachers', 'user', 'course'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'published_at' => ['nullable', 'date'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'certificate_front_background' => ['nullable', 'image', 'max:4096'],
            'certificate_back_background' => ['nullable', 'image', 'max:4096'],
        ]);

        $ownerId = $user->isAdmin()
            ? ($validated['owner_id'] ?? $user->id)
            : $user->id;

        $course = Course::create([
            'owner_id' => $ownerId,
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title']),
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
        ]);

        $this->syncBrandingUploads($request, $course);

        return redirect()->route('courses.edit', $course)->with('status', 'Curso criado com sucesso.');
    }

    public function edit(Request $request, Course $course): View
    {
        $user = $request->user();
        $this->ensureCanManageCourse($user, $course);

        $course->load([
            'modules.lessons' => fn ($query) => $query->orderBy('position'),
            'finalTest',
            'certificateBranding',
        ]);

        $teachers = $this->teachers();

        return view('courses.edit', compact('course', 'teachers', 'user'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();
        $this->ensureCanManageCourse($user, $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'published_at' => ['nullable', 'date'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'certificate_front_background' => ['nullable', 'image', 'max:4096'],
            'certificate_back_background' => ['nullable', 'image', 'max:4096'],
        ]);

        $course->fill([
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
        ]);

        if ($user->isAdmin() && isset($validated['owner_id'])) {
            $course->owner_id = $validated['owner_id'];
        }

        if ($course->isDirty('title')) {
            $course->slug = $this->generateUniqueSlug($course->title, $course->id);
        }

        $course->save();

        $this->syncBrandingUploads($request, $course);

        return back()->with('status', 'Curso atualizado.');
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();
        $this->ensureCanManageCourse($user, $course);

        $course->delete();

        return redirect()->route('courses.index')->with('status', 'Curso removido.');
    }

    private function teachers()
    {
        return User::query()
            ->whereIn('role', [UserRole::TEACHER->value, UserRole::ADMIN->value])
            ->orderBy('name')
            ->get();
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Course::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function syncBrandingUploads(Request $request, Course $course): void
    {
        if (! $request->hasFile('certificate_front_background') && ! $request->hasFile('certificate_back_background')) {
            return;
        }

        /** @var CertificateBranding $branding */
        $branding = $course->certificateBranding()->firstOrNew([]);

        if ($request->hasFile('certificate_front_background')) {
            if ($branding->front_background_path) {
                Storage::disk('public')->delete($branding->front_background_path);
            }

            $branding->front_background_path = $request->file('certificate_front_background')
                ->store('certificate-backgrounds', 'public');
        }

        if ($request->hasFile('certificate_back_background')) {
            if ($branding->back_background_path) {
                Storage::disk('public')->delete($branding->back_background_path);
            }

            $branding->back_background_path = $request->file('certificate_back_background')
                ->store('certificate-backgrounds', 'public');
        }

        $branding->course()->associate($course);
        $branding->save();
    }
}
