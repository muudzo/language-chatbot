<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

// Landing page (redirect to chatbot)
Route::get('/', function () {
    return redirect()->route('chatbot.index');
});

// Chatbot routes
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/message', [ChatbotController::class, 'processMessage'])->name('chatbot.message');
Route::post('/chatbot/language', [ChatbotController::class, 'switchLanguage'])->name('chatbot.language');
Route::get('/chatbot/phrases', [ChatbotController::class, 'getPhrases'])->name('chatbot.phrases');