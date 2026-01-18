<?php

use App\Http\Controllers\Api\CourseCatalogController;
use App\Http\Controllers\Api\KavooWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('cursos', CourseCatalogController::class);
Route::post('kavoo/webhook', KavooWebhookController::class);
