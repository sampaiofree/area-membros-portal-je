@extends('layouts.student')

@section('title', $course->title)

@section('content')
    @php
        $settings = \App\Models\SystemSetting::current();
        $frontImage = $settings->assetUrl('default_certificate_front_path');
        $backImage = $settings->assetUrl('default_certificate_back_path');
        $studentName = auth()->check() ? auth()->user()->preferredName() : 'Seu nome aqui';
        $programText = $course->modules->flatMap(fn ($module) => [
            "Modulo {$module->position} - {$module->title}",
            ...$module->lessons->map(fn ($lesson) => "{$lesson->position}. {$lesson->title}")->all(),
            '',
        ])->implode("\n");
    @endphp

    <article class="space-y-8">
        <section class="rounded-card bg-white shadow-card overflow-hidden">
            @if ($course->promo_video_url)
                <div class="aspect-video w-full">
                    <iframe src="{{ $course->promo_video_url }}" class="h-full w-full" allowfullscreen loading="lazy"></iframe>
                </div>
            @elseif ($course->coverImageUrl())
                <img src="{{ $course->coverImageUrl() }}" alt="{{ $course->title }}" class="h-64 w-full object-cover md:h-96">
            @endif
            <div class="space-y-3 p-6">
                <p class="text-xs uppercase tracking-wide text-edux-primary">Curso online</p>
                <h1 class="font-display text-3xl text-edux-primary">{{ $course->title }}</h1>
                <p class="text-slate-600">{{ $course->description }}</p>
                <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                    <span>Alunos: <strong class="text-edux-primary">{{ $studentCount }}</strong></span>
                    <span>Carga horaria: <strong class="text-edux-primary">{{ $course->duration_minutes ?? '---' }} min</strong></span>
                </div>
                @auth
                    <form method="POST" action="{{ route('learning.courses.enroll', $course) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="edux-btn w-full inline-flex justify-center">Inscreva-se gratis</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="edux-btn mt-4 inline-flex">Crie sua conta para se inscrever</a>
                @endauth
            </div>
        </section>

        <section class="rounded-card bg-white p-6 shadow-card space-y-4">
            <h2 class="text-2xl font-display text-edux-primary">Conteudo programatico</h2>
            <div class="space-y-3">
                @foreach ($course->modules as $module)
                    <article class="rounded-2xl border border-edux-line/70 p-4">
                        <p class="text-sm font-semibold text-edux-primary">Modulo {{ $module->position }} - {{ $module->title }}</p>
                        <ul class="mt-2 space-y-1 text-sm text-slate-600">
                            @foreach ($module->lessons as $lesson)
                                <li>- {{ $lesson->title }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="rounded-card bg-white p-6 shadow-card space-y-4" x-data="{ modal: null }" @keydown.escape.window="modal = null">
            <h2 class="text-2xl font-display text-edux-primary">Modelo do certificado</h2>
            <p class="text-sm text-slate-600">A imagem abaixo ja combina o fundo com os textos do curso e do aluno, apenas para visualizacao.</p>
            <div class="grid gap-4 md:grid-cols-2">
                <figure class="overflow-hidden rounded-2xl border border-edux-line/70 bg-edux-background" @click="modal = $refs.certFront?.src">
                    <img data-cert-front data-base="{{ $frontImage }}" data-course="{{ $course->title }}" data-student="{{ $studentName }}" alt="Modelo frente do certificado" class="w-full cursor-zoom-in object-cover transition hover:scale-[1.01]" x-ref="certFront">
                    <figcaption class="px-3 py-2 text-center text-xs text-slate-500">Clique para ampliar</figcaption>
                </figure>
                <figure class="overflow-hidden rounded-2xl border border-edux-line/70 bg-edux-background" @click="modal = $refs.certBack?.src">
                    <img data-cert-back data-base="{{ $backImage }}" data-course="{{ $course->title }}" data-text="{{ $programText }}" alt="Modelo verso do certificado" class="w-full cursor-zoom-in object-cover transition hover:scale-[1.01]" x-ref="certBack">
                    <figcaption class="px-3 py-2 text-center text-xs text-slate-500">Clique para ampliar</figcaption>
                </figure>
            </div>

            <div x-show="modal" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" @click="modal = null">
                <img :src="modal" alt="Certificado ampliado" class="max-h-[90vh] max-w-full rounded-2xl shadow-2xl">
            </div>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const frontTarget = document.querySelector('[data-cert-front]');
                        const backTarget = document.querySelector('[data-cert-back]');

                        const splitLines = (ctx, text, maxWidth) => {
                            const words = text.split(' ');
                            const lines = [];
                            let line = '';

                            words.forEach((word, idx) => {
                                const testLine = `${line}${word} `;
                                if (ctx.measureText(testLine).width > maxWidth && idx > 0) {
                                    lines.push(line.trim());
                                    line = `${word} `;
                                } else {
                                    line = testLine;
                                }
                            });
                            lines.push(line.trim());
                            return lines;
                        };

                        const compose = (baseUrl, title, subtitle, target, options = {}) => {
                            if (!baseUrl || !target) return;
                            const img = new Image();
                            img.crossOrigin = 'anonymous';
                            img.onload = () => {
                                const canvas = document.createElement('canvas');
                                canvas.width = img.width;
                                canvas.height = img.height;
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(img, 0, 0);

                                const paddingX = canvas.width * 0.05;
                                const paddingY = canvas.height * 0.08;
                                const centerX = canvas.width / 2;
                                ctx.textAlign = 'center';

                                ctx.fillStyle = 'rgba(17, 24, 39, 0.8)';
                                ctx.font = `${Math.round(canvas.width * 0.035)}px "Inter", Arial, sans-serif`;
                                ctx.fillText(title, centerX, canvas.height * 0.42);

                                if (subtitle) {
                                    ctx.fillStyle = 'rgba(37, 99, 235, 0.9)';
                                    ctx.font = `${Math.round(canvas.width * 0.028)}px "Inter", Arial, sans-serif`;
                                    ctx.fillText(subtitle, centerX, canvas.height * 0.48);
                                }

                                if (options.watermark) {
                                    ctx.save();
                                    ctx.translate(canvas.width / 2, canvas.height / 2);
                                    ctx.rotate(-Math.PI / 6);
                                    const gradient = ctx.createLinearGradient(-canvas.width / 2, 0, canvas.width / 2, canvas.height / 2);
                                    gradient.addColorStop(0, 'rgba(255,255,255,0)');
                                    gradient.addColorStop(0.5, 'rgba(59,130,246,0.14)');
                                    gradient.addColorStop(1, 'rgba(0,0,0,0)');
                                    ctx.fillStyle = gradient;
                                    ctx.font = `bold ${Math.round(canvas.width * 0.16)}px "Inter", Arial, sans-serif`;
                                    ctx.textAlign = 'center';
                                    ctx.fillText('MODELO', 0, 0);
                                    ctx.restore();
                                }

                                if (options.bodyText) {
                                    ctx.fillStyle = 'rgba(55, 65, 81, 0.95)';
                                    ctx.font = `${Math.round(canvas.width * 0.023)}px "Inter", Arial, sans-serif`;
                                    ctx.textAlign = 'center';
                                    const maxWidth = canvas.width - paddingX * 2;
                                    const lines = splitLines(ctx, options.bodyText, maxWidth);
                                    const lineHeight = canvas.width * 0.03;
                                    const totalHeight = lines.length * lineHeight;
                                    let y = (canvas.height - totalHeight) / 2;
                                    lines.forEach((line) => {
                                        ctx.fillText(line, centerX, y);
                                        y += lineHeight;
                                    });
                                }

                                target.src = canvas.toDataURL('image/png');
                            };
                            img.src = baseUrl;
                        };

                        if (frontTarget) {
                            compose(
                                frontTarget.dataset.base,
                                frontTarget.dataset.course || 'Curso',
                                frontTarget.dataset.student || 'Aluno',
                                frontTarget,
                                { watermark: true }
                            );
                        }

                        if (backTarget) {
                            compose(
                                backTarget.dataset.base,
                                backTarget.dataset.course || 'Conteudo',
                                'Programa do curso',
                                backTarget,
                                { bodyText: backTarget.dataset.text || '', watermark: false }
                            );
                        }
                    });
                </script>
            @endpush
        </section>

        <section class="rounded-card bg-white p-6 shadow-card space-y-4">
            <h2 class="text-2xl font-display text-edux-primary">Perguntas frequentes</h2>
            @foreach ([
                ['title' => 'O curso e totalmente gratuito?', 'body' => 'Sim, voce pode assistir todas as aulas sem custo. Apenas o certificado exige pagamento quando disponivel.'],
                ['title' => 'Por quanto tempo terei acesso?', 'body' => 'Enquanto mantivermos o curso publicado voce podera reassistir quantas vezes quiser.'],
                ['title' => 'Preciso fazer prova?', 'body' => $course->finalTest ? 'Sim, o curso possui teste final para liberar o certificado.' : 'Nao, basta concluir todas as aulas.'],
            ] as $faq)
                <details class="rounded-2xl border border-edux-line/70 p-4">
                    <summary class="cursor-pointer text-sm font-semibold text-edux-primary">{{ $faq['title'] }}</summary>
                    <p class="mt-2 text-sm text-slate-600">{{ $faq['body'] }}</p>
                </details>
            @endforeach
        </section>
    </article>

    <div class="fixed inset-x-0 bottom-20 z-40 border-t border-edux-line bg-white p-4 shadow-2xl md:hidden md:bottom-0">
        @auth
            <form method="POST" action="{{ route('learning.courses.enroll', $course) }}">
                @csrf
                <button type="submit" class="edux-btn w-full">Inscreva-se gratis</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="edux-btn w-full">Crie sua conta para se inscrever</a>
        @endauth
    </div>
@endsection
