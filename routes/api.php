<?php

use App\Http\Controllers\Api\KavooWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('kavoo/webhook', KavooWebhookController::class);
