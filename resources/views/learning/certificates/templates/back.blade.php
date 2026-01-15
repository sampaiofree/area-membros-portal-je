@php
    $mode = $mode ?? 'preview';
    $backgroundUrl = $branding?->back_background_url;
    $paragraphs = [];

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
