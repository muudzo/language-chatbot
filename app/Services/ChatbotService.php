<?php

namespace App\Services;

use App\Models\Phrase;
use App\Models\Conversation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ChatbotService
{
    private const CACHE_TTL = 3600; // 1 hour

    public function processMessage(string $message, string $language): array
    {
        try {
            // Save conversation
            $conversation = $this->saveConversation($message, $language);
            
            // Generate response
            $response = $this->generateResponse($message, $language);
            
            // Update conversation with response
            $conversation->update(['bot_response' => $response]);
            
            return [
                'response' => $response,
                'language' => $language,
                'success' => true
            ];
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            return [
                'response' => 'Sorry, there was an error processing your request.',
                'success' => false
            ];
        }
    }

    private function saveConversation(string $message, string $language): Conversation
    {
        return Conversation::create([
            'user_message' => $message,
            'language' => $language
        ]);
    }

    private function generateResponse(string $message, string $language): string
    {
        $cleanMessage = strtolower(trim($message));
        
        // Check if it's a training input
        if (str_contains($cleanMessage, ':')) {
            [$english, $native] = explode(':', $cleanMessage, 2);
            $this->learnPhrase(trim($english), trim($native), $language);
            return "Thank you! I've learned that \"$english\" in $language is \"$native\".";
        }
        
        // Look for exact match
        $phrase = Phrase::where('english', $cleanMessage)
                       ->where('language', $language)
                       ->first();
                       
        if ($phrase) {
            return "In $language, \"$cleanMessage\" is \"$phrase->native\".";
        }
        
        // Look for partial match
        $phrase = Phrase::where('english', 'like', "%$cleanMessage%")
                       ->where('language', $language)
                       ->first();
                       
        if ($phrase) {
            return "I found something similar: In $language, \"$phrase->english\" is \"$phrase->native\".";
        }
        
        return "I don't know how to say \"$cleanMessage\" in $language yet. You can teach me by typing \"$cleanMessage:native translation\".";
    }

    private function learnPhrase(string $english, string $native, string $language): void
    {
        Phrase::updateOrCreate(
            [
                'english' => strtolower(trim($english)),
                'language' => $language
            ],
            ['native' => trim($native)]
        );
    }

    public function getPhrases(string $language): Collection
    {
        return Phrase::where('language', $language)
                    ->orderBy('english')
                    ->get(['english', 'native', 'context']);
    }

    public function findPhrase(string $message, string $language): ?Phrase
    {
        $cacheKey = "phrase_{$language}_" . md5($message);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($message, $language) {
            return Phrase::where('language', $language)
                ->where(function ($query) use ($message) {
                    $query->where('english', 'like', $message)
                          ->orWhere('english', 'like', "%$message%");
                })
                ->first();
        });
    }

    public function getLanguagePhrases(string $language)
    {
        $cacheKey = "phrases_${language}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($language) {
            return Phrase::where('language', $language)
                        ->orderBy('english')
                        ->get(['english', 'native', 'context']);
        });
    }
} 