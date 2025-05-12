<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatbotRequest;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ChatbotController extends Controller
{
    protected ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function index(): View
    {
        return view('chatbot.index');
    }
    
    public function processMessage(ChatbotRequest $request): JsonResponse
    {
        $response = $this->chatbotService->processMessage(
            $request->input('message'),
            $request->input('language', 'shona')
        );

        return response()->json($response);
    }

    public function switchLanguage(ChatbotRequest $request): JsonResponse
    {
        $language = $request->input('language', 'shona');
        
        return response()->json([
            'language' => $language,
            'success' => true
        ]);
    }

    public function getPhrases(ChatbotRequest $request): JsonResponse
    {
        $phrases = $this->chatbotService->getPhrases($request->input('language', 'shona'));
        
        return response()->json([
            'phrases' => $phrases,
            'count' => $phrases->count(),
            'language' => $request->input('language', 'shona')
        ]);
    }
} 