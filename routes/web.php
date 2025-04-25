<?php

use App\Http\Controllers\ChatbotController;

Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/message', [ChatbotController::class, 'processMessage'])->name('chatbot.message');
Route::post('/chatbot/language', [ChatbotController::class, 'switchLanguage'])->name('chatbot.language');