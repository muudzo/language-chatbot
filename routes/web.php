<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

// Landing page (redirect to chatbot)
Route::get('/', function () {
    return redirect()->route('chatbot.index');
});

// Chatbot routes
Route::controller(ChatbotController::class)->group(function () {
    Route::get('/chatbot', 'index')->name('chatbot.index');
    Route::post('/chatbot/message', 'processMessage')->name('chatbot.message');
    Route::post('/chatbot/language', 'switchLanguage')->name('chatbot.language');
    Route::get('/chatbot/phrases', 'getPhrases')->name('chatbot.phrases');
});