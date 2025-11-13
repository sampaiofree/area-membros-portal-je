@php
    $frontStyle = "font-family:'Inter',Arial,sans-serif; padding:2rem; border:4px solid #0f172a; border-radius:20px; position:relative; overflow:hidden;";
    if (!empty($branding?->front_background_url)) {
        $frontStyle .= "background-image:url('{$branding->front_background_url}'); background-size:cover; background-position:center;";
    }
@endphp

<div style="{{ $frontStyle }}">
    <div style="position:relative; z-index:2;">
        <p style="text-transform:uppercase; letter-spacing:0.4em; font-size:0.85rem; color:#94a3b8; margin:0;">Certificado</p>
        <h1 style="font-size:2rem; margin:0.5rem 0;">{{ $course->title }}</h1>
        <p style="font-size:1.1rem; margin:1.5rem 0;">
            Certificamos que <strong>{{ $displayName }}</strong> concluiu com êxito o curso acima em {{ $issuedAt->format('d/m/Y') }}.
        </p>
        <p style="font-size:0.9rem; color:#475569;">Carga horária total: {{ $course->duration_minutes ?? '---' }} minutos.</p>
    </div>
    <div style="position:absolute; bottom:1.5rem; right:1.5rem; z-index:2; background:#ffffffcc; padding:0.5rem; border-radius:8px; text-align:center;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($publicUrl) }}" alt="QR Code" style="width:120px; height:120px;">
        <p style="margin:0.25rem 0 0 0; font-size:0.65rem;">Verifique: {{ $publicUrl }}</p>
    </div>
</div>
