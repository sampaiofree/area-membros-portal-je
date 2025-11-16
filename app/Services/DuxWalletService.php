<?php

namespace App\Services;

use App\Models\DuxRule;
use App\Models\DuxTransaction;
use App\Models\DuxWallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DuxWalletService
{
    public function walletFor(User $user): DuxWallet
    {
        return DuxWallet::firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
    }

    public function applyRule(User $user, string $ruleSlug, array $meta = []): DuxTransaction
    {
        $rule = DuxRule::where('slug', $ruleSlug)->where('active', true)->first();
        if (! $rule) {
            throw new RuntimeException("Regra {$ruleSlug} nao encontrada ou inativa.");
        }

        return $rule->direction === 'earn'
            ? $this->earn($user, $rule, $meta)
            : $this->spend($user, $rule, $meta);
    }

    public function earn(User $user, DuxRule $rule, array $meta = []): DuxTransaction
    {
        return DB::transaction(function () use ($user, $rule, $meta) {
            $wallet = $this->walletFor($user);
            $wallet->increment('balance', $rule->amount);

            return DuxTransaction::create([
                'wallet_id' => $wallet->id,
                'rule_id' => $rule->id,
                'direction' => 'earn',
                'amount' => $rule->amount,
                'source' => $rule->slug,
                'meta' => $meta,
            ]);
        });
    }

    public function spend(User $user, DuxRule $rule, array $meta = []): DuxTransaction
    {
        return DB::transaction(function () use ($user, $rule, $meta) {
            $wallet = $this->walletFor($user);

            if ($wallet->balance < $rule->amount) {
                throw new RuntimeException('Saldo insuficiente de duxes.');
            }

            $wallet->decrement('balance', $rule->amount);

            return DuxTransaction::create([
                'wallet_id' => $wallet->id,
                'rule_id' => $rule->id,
                'direction' => 'spend',
                'amount' => -1 * (int) $rule->amount,
                'source' => $rule->slug,
                'meta' => $meta,
            ]);
        });
    }

    public function adjust(User $user, int $amount, string $source, array $meta = []): DuxTransaction
    {
        return DB::transaction(function () use ($user, $amount, $source, $meta) {
            $wallet = $this->walletFor($user);
            $wallet->increment('balance', $amount);

            return DuxTransaction::create([
                'wallet_id' => $wallet->id,
                'rule_id' => null,
                'direction' => $amount >= 0 ? 'earn' : 'spend',
                'amount' => $amount,
                'source' => $source,
                'meta' => $meta,
            ]);
        });
    }
}
