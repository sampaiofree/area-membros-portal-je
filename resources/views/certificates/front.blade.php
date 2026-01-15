@php
    $backgroundUrl = $backgroundImagePath
        ? 'file://'.str_replace('\\', '/', $backgroundImagePath)
        : null;
    $courseStartLabel = $courseStartLabel ?? '01/01/2024';
    $courseEndLabel = $courseEndLabel ?? $completedAtLabel ?? 'DATA FINAL';
    $workloadLabel = $workloadLabel ?? 'x horas';
    $qrUrl = $qrDataUri ?? null;
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
        variant="front"
        mode="pdf"
        :student-name="$studentName"
        :course-name="$courseName"
        :completed-at-label="$completedAtLabel"
        :workload-label="$workloadLabel"
        :completed-at-start-label="$courseStartLabel"
        :completed-at-end-label="$courseEndLabel"
        :cpf="$cpf"
        :background="$backgroundUrl"
        :show-watermark="false"
        :qr-url="$qrUrl"
    />
</div>
</body>
</html>
