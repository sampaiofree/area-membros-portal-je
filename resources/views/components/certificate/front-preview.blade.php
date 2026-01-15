@props([
    'studentName' => null,
    'courseName' => null,
    'completedAt' => null,
    'workload' => null,
    'completedAtStart' => null,
    'completedAtEnd' => null,
    'issuerPortal' => null,
    'issuerInstitution' => null,
    'cpf' => null,
    'background' => null,
])

<x-certificate.layout
    variant="front"
    mode="preview"
    :student-name="$studentName"
    :course-name="$courseName"
    :completed-at-label="$completedAt"
    :workload-label="$workload"
    :completed-at-start-label="$completedAtStart"
    :completed-at-end-label="$completedAtEnd"
    :issuer-portal="$issuerPortal"
    :issuer-institution="$issuerInstitution"
    :cpf="$cpf"
    :background="$background"
    :show-watermark="true"
/>
