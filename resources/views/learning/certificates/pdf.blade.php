<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .page {
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: auto;
        }
    </style>
</head>
<body>
    <div class="page">
        {!! $certificate->front_content !!}
    </div>
    <div class="page">
        {!! $certificate->back_content !!}
    </div>
</body>
</html>
