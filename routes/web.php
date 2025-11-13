<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateBrandingController;
use App\Http\Controllers\CourseCertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinalTestController;
use App\Http\Controllers\FinalTestQuestionController;
use App\Http\Controllers\FinalTestQuestionOptionController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonProgressController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PublicCertificateController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\StudentFinalTestController;
use App\Http\Controllers\StudentProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::get('/certificates/verify/{token}', PublicCertificateController::class)->name('certificates.verify');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:admin,teacher')->group(function (): void {
        Route::resource('courses', CourseController::class)->except(['show']);

        Route::resource('courses.modules', ModuleController::class)
            ->shallow()
            ->only(['create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('modules.lessons', LessonController::class)
            ->shallow()
            ->only(['create', 'store', 'edit', 'update', 'destroy']);

        Route::get('courses/{course}/final-test/create', [FinalTestController::class, 'create'])
            ->name('courses.final-test.create');
        Route::post('courses/{course}/final-test', [FinalTestController::class, 'store'])
            ->name('courses.final-test.store');
        Route::get('courses/{course}/final-test/{finalTest}/edit', [FinalTestController::class, 'edit'])
            ->name('courses.final-test.edit');
        Route::put('courses/{course}/final-test/{finalTest}', [FinalTestController::class, 'update'])
            ->name('courses.final-test.update');
        Route::delete('courses/{course}/final-test/{finalTest}', [FinalTestController::class, 'destroy'])
            ->name('courses.final-test.destroy');

        Route::get('courses/{course}/final-test/{finalTest}/questions', [FinalTestQuestionController::class, 'index'])
            ->name('courses.final-test.questions.index');
        Route::get('courses/{course}/final-test/{finalTest}/questions/create', [FinalTestQuestionController::class, 'create'])
            ->name('courses.final-test.questions.create');
        Route::post('courses/{course}/final-test/{finalTest}/questions', [FinalTestQuestionController::class, 'store'])
            ->name('courses.final-test.questions.store');
        Route::get('courses/{course}/final-test/{finalTest}/questions/{question}/edit', [FinalTestQuestionController::class, 'edit'])
            ->name('courses.final-test.questions.edit');
        Route::put('courses/{course}/final-test/{finalTest}/questions/{question}', [FinalTestQuestionController::class, 'update'])
            ->name('courses.final-test.questions.update');
        Route::delete('courses/{course}/final-test/{finalTest}/questions/{question}', [FinalTestQuestionController::class, 'destroy'])
            ->name('courses.final-test.questions.destroy');

        Route::get('courses/{course}/final-test/{finalTest}/questions/{question}/options/create', [FinalTestQuestionOptionController::class, 'create'])
            ->name('courses.final-test.questions.options.create');
        Route::post('courses/{course}/final-test/{finalTest}/questions/{question}/options', [FinalTestQuestionOptionController::class, 'store'])
            ->name('courses.final-test.questions.options.store');
        Route::get('courses/{course}/final-test/{finalTest}/questions/{question}/options/{option}/edit', [FinalTestQuestionOptionController::class, 'edit'])
            ->name('courses.final-test.questions.options.edit');
        Route::put('courses/{course}/final-test/{finalTest}/questions/{question}/options/{option}', [FinalTestQuestionOptionController::class, 'update'])
            ->name('courses.final-test.questions.options.update');
        Route::delete('courses/{course}/final-test/{finalTest}/questions/{question}/options/{option}', [FinalTestQuestionOptionController::class, 'destroy'])
            ->name('courses.final-test.questions.options.destroy');
    });

    Route::middleware('role:admin')->group(function (): void {
        Route::get('certificates/branding', [CertificateBrandingController::class, 'edit'])
            ->name('certificates.branding.edit');
        Route::put('certificates/branding', [CertificateBrandingController::class, 'update'])
            ->name('certificates.branding.update');
    });

    Route::prefix('learning')
        ->middleware('role:student')
        ->name('learning.')
        ->group(function (): void {
            Route::get('courses/{course:slug}', [StudentCourseController::class, 'redirectToNextLesson'])
                ->name('courses.show');
            Route::get('courses/{course:slug}/lessons/{lesson}', [StudentCourseController::class, 'lesson'])
                ->name('courses.lessons.show');
            Route::post('courses/{course:slug}/lessons/{lesson}/complete', [LessonProgressController::class, 'store'])
                ->name('courses.lessons.complete');

            Route::get('profile/name', [StudentProfileController::class, 'editName'])
                ->name('profile.name.edit');
            Route::put('profile/name', [StudentProfileController::class, 'updateName'])
                ->name('profile.name.update');

            Route::get('courses/{course:slug}/final-test', [StudentFinalTestController::class, 'intro'])
                ->name('courses.final-test.intro');
            Route::post('courses/{course:slug}/final-test/start', [StudentFinalTestController::class, 'start'])
                ->name('courses.final-test.start');
            Route::get('courses/{course:slug}/final-test/attempt/{attempt}', [StudentFinalTestController::class, 'attempt'])
                ->name('courses.final-test.attempt');
            Route::post('courses/{course:slug}/final-test/attempt/{attempt}', [StudentFinalTestController::class, 'submit'])
                ->name('courses.final-test.submit');

            Route::post('courses/{course:slug}/certificate', [CourseCertificateController::class, 'store'])
                ->name('courses.certificate.store');
            Route::get('courses/{course:slug}/certificate/{certificate}', [CourseCertificateController::class, 'show'])
                ->name('courses.certificate.show');
            Route::get('courses/{course:slug}/certificate/{certificate}/download', [CourseCertificateController::class, 'download'])
                ->name('courses.certificate.download');
        });
});
