@php
    $kavoo ??= null;
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Nome completo</span>
        <input
            type="text"
            name="customer_name"
            value="{{ old('customer_name', $kavoo?->customer_name ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('customer_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Primeiro nome</span>
        <input
            type="text"
            name="customer_first_name"
            value="{{ old('customer_first_name', $kavoo?->customer_first_name ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('customer_first_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Sobrenome</span>
        <input
            type="text"
            name="customer_last_name"
            value="{{ old('customer_last_name', $kavoo?->customer_last_name ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('customer_last_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>E-mail</span>
        <input
            type="email"
            name="customer_email"
            value="{{ old('customer_email', $kavoo?->customer_email ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('customer_email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Telefone</span>
        <input
            type="text"
            name="customer_phone"
            value="{{ old('customer_phone', $kavoo?->customer_phone ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('customer_phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>ID do produto</span>
        <input
            type="number"
            min="0"
            name="item_product_id"
            value="{{ old('item_product_id', $kavoo?->item_product_id ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('item_product_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Nome do produto</span>
        <input
            type="text"
            name="item_product_name"
            value="{{ old('item_product_name', $kavoo?->item_product_name ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('item_product_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Código da transação</span>
        <input
            type="text"
            name="transaction_code"
            value="{{ old('transaction_code', $kavoo?->transaction_code ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('transaction_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Código do status</span>
        <input
            type="text"
            name="status_code"
            value="{{ old('status_code', $kavoo?->status_code ?? '') }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('status_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
</div>
