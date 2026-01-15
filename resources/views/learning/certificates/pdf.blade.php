<!DOCTYPE html>
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
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            height: 100%;
            page-break-after: always;
            page-break-inside: avoid;
            overflow: hidden;
        }
        .page:last-child {
            page-break-after: auto;
        }
    </style>
</head>
<body>
    <div class="page">
        {!! $frontContent !!}
    </div>
    <div class="page">
        {!! $backContent !!}
    </div>
</body>
</html>
