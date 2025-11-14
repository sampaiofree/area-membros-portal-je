<section class="space-y-6">
    <div class="rounded-card bg-white p-6 shadow-card space-y-4">
        <div>
            <p class="text-sm uppercase tracking-wide text-edux-primary">Pagamentos</p>
            <h1 class="font-display text-2xl text-edux-primary">Registrar certificado</h1>
            <p class="text-slate-600 text-sm">Use este formulário para registrar o pagamento do certificado antes da emissão.</p>
        </div>

        <form wire:submit.prevent="save" class="grid gap-4 md:grid-cols-2">
            <label class="text-sm font-semibold text-slate-600 space-y-1">
                <span>Aluno</span>
                <select wire:model.defer="user_id" class="w-full rounded-xl border border-edux-line px-4 py-3">
                    <option value="">Selecione um aluno</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </label>

            <label class="text-sm font-semibold text-slate-600 space-y-1">
                <span>Curso</span>
                <select wire:model.defer="course_id" class="w-full rounded-xl border border-edux-line px-4 py-3">
                    <option value="">Selecione um curso</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </label>

            <label class="text-sm font-semibold text-slate-600 space-y-1">
                <span>Valor</span>
                <input type="number" step="0.01" wire:model.defer="amount" class="w-full rounded-xl border border-edux-line px-4 py-3">
                @error('amount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </label>

            <label class="text-sm font-semibold text-slate-600 space-y-1">
                <span>Status</span>
                <select wire:model.defer="status" class="w-full rounded-xl border border-edux-line px-4 py-3">
                    <option value="pending">Pendente</option>
                    <option value="paid">Pago</option>
                    <option value="failed">Falhou</option>
                </select>
                @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </label>

            <label class="text-sm font-semibold text-slate-600 space-y-1">
                <span>Referência</span>
                <input type="text" wire:model.defer="transaction_reference" class="w-full rounded-xl border border-edux-line px-4 py-3" placeholder="ID da transação, PIX, etc.">
            </label>

            <label class="text-sm font-semibold text-slate-600 space-y-1">
                <span>Data do pagamento</span>
                <input type="datetime-local" wire:model.defer="paid_at" class="w-full rounded-xl border border-edux-line px-4 py-3">
            </label>

            <div class="md:col-span-2">
                <button type="submit" class="edux-btn">Salvar pagamento</button>
            </div>
        </form>
    </div>

    <div class="rounded-card bg-white p-6 shadow-card space-y-4">
        <h2 class="text-xl font-display text-edux-primary">Histórico</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500">
                        <th class="py-2">Aluno</th>
                        <th>Curso</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Pago em</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr class="border-t border-edux-line/60">
                            <td class="py-2">{{ $payment->user->preferredName() }}</td>
                            <td>{{ $payment->course->title }}</td>
                            <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                            <td>
                                <span @class([
                                    'px-2 py-1 rounded-full text-xs font-semibold',
                                    'bg-emerald-100 text-emerald-700' => $payment->status === 'paid',
                                    'bg-amber-100 text-amber-700' => $payment->status === 'pending',
                                    'bg-red-100 text-red-700' => $payment->status === 'failed',
                                ])>
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ optional($payment->paid_at)->format('d/m/Y H:i') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-500">Nenhum pagamento registrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $payments->links() }}
    </div>
</section>
