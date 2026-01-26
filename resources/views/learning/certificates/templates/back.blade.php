@php
use Illuminate\Support\Facades\Storage;

    $mode = $mode ?? 'preview';
    $backgroundUrl = $branding?->back_background_url;
    $paragraphs = [];

    if ($mode === 'pdf' && $branding?->back_background_path) {
        $path = ltrim($branding->back_background_path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        $disk = Storage::disk('public');
        if ($disk->exists($path)) {
            $absolutePath = str_replace('\\', '/', $disk->path($path));
            if (preg_match('/^[A-Za-z]:\\//', $absolutePath)) {
                $absolutePath = '/' . $absolutePath;
            }
            $backgroundUrl = 'file://' . $absolutePath;
        }
    }

    if ($course?->modules) {
        foreach ($course->modules as $moduleIndex => $module) {
            $moduleNumber = $module->position ?: ($moduleIndex + 1);
            $line = "Módulo {$moduleNumber}: {$module->title}";

            if ($module->lessons->isNotEmpty()) {
                $lessonFragments = $module->lessons->values()->map(function ($lesson, $lessonIndex) {
                    $lessonNumber = $lesson->position ?: ($lessonIndex + 1);
                    return "Aula {$lessonNumber}: {$lesson->title}";
                });

                $line .= '. ' . $lessonFragments->implode('. ') . '.';
            } else {
                $line .= '.';
            }

            $paragraphs[] = $line;
        }
    }
@endphp

<x-certificate.layout
    variant="back"
    :mode="$mode"
    :background="$backgroundUrl"
    :paragraphs="$paragraphs"
    :show-watermark="false"
/>
