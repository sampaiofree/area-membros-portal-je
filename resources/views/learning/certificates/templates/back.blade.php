@php
    $backStyle = "font-family:'Inter',Arial,sans-serif; padding:2rem; border:2px dashed #94a3b8; border-radius:20px;";
    if (!empty($branding?->back_background_url)) {
        $backStyle .= "background-image:url('{$branding->back_background_url}'); background-size:cover; background-position:center;";
    }
@endphp

<div style="{{ $backStyle }}">
    <h2 style="margin-top:0;">Conteúdo programático</h2>
    <ol style="padding-left:1.25rem;">
        @foreach ($course->modules as $module)
            <li style="margin-bottom:0.75rem;">
                <strong>{{ $module->title }}</strong>
                <ul style="padding-left:1.25rem; margin:0.25rem 0 0 0;">
                    @foreach ($module->lessons as $lesson)
                        <li>{{ $lesson->title }}</li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ol>
</div>
