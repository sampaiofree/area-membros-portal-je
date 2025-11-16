<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rules = [
            ['name' => 'Concluir aula', 'slug' => 'lesson_completed', 'direction' => 'earn', 'amount' => 1, 'type' => 'fixed', 'model' => 'lesson_completed'],
            ['name' => 'Finalizar curso', 'slug' => 'course_completed', 'direction' => 'earn', 'amount' => 3, 'type' => 'fixed', 'model' => 'course_completed'],
            ['name' => 'Passar na prova', 'slug' => 'test_passed', 'direction' => 'earn', 'amount' => 5, 'type' => 'fixed', 'model' => 'test_passed'],
            ['name' => 'Nota maxima na prova', 'slug' => 'test_max_score', 'direction' => 'earn', 'amount' => 10, 'type' => 'fixed', 'model' => 'test_passed'],
            ['name' => 'Matricular em novo curso', 'slug' => 'enrollment', 'direction' => 'spend', 'amount' => 20, 'type' => 'fixed', 'model' => 'enrollment'],
            ['name' => 'Refazer prova', 'slug' => 'test_retry', 'direction' => 'spend', 'amount' => 5, 'type' => 'fixed', 'model' => 'test_retry'],
            ['name' => 'Emitir certificado', 'slug' => 'certificate_fee', 'direction' => 'spend', 'amount' => 50, 'type' => 'fixed', 'model' => 'certificate'],
        ];

        DB::table('dux_rules')->upsert(
            collect($rules)->map(fn ($rule) => $rule + ['active' => true, 'conditions' => null, 'created_at' => $now, 'updated_at' => $now])->all(),
            ['slug'],
            ['name', 'direction', 'amount', 'type', 'model', 'active', 'conditions', 'updated_at']
        );
    }

    public function down(): void
    {
        DB::table('dux_rules')->whereIn('slug', [
            'lesson_completed',
            'course_completed',
            'test_passed',
            'test_max_score',
            'enrollment',
            'test_retry',
            'certificate_fee',
        ])->delete();
    }
};
