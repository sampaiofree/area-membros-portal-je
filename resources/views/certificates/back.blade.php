@php
    $backgroundUrl = $backgroundImagePath
        ? 'file://'.str_replace('\\', '/', $backgroundImagePath)
        : null;
    $paragraphs = $paragraphs ?? [];
@endphp

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            font-family: 'Helvetica', Arial, sans-serif;
            color: #0f172a;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div class="page">
    <x-certificate.layout
        variant="back"
        mode="pdf"
        :background="$backgroundUrl"
        :paragraphs="$paragraphs"
        :show-watermark="false"
    />
</div>
</body>
</html>
