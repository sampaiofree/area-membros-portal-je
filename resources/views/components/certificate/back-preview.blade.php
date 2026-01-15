@props([
    'background' => null,
    'paragraphs' => [],
])

<x-certificate.layout
    variant="back"
    mode="preview"
    :background="$background"
    :paragraphs="$paragraphs"
    :show-watermark="true"
/>
