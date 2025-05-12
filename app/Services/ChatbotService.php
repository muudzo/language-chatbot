<?php

namespace App\Services;

use App\Models\Phrase;
use Illuminate\Support\Facades\Cache;

class ChatbotService
{
    private const CACHE_TTL = 3600; // 1 hour

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

    public function learnPhrase(string $english, string $native, string $language): Phrase
    {
        $phrase = Phrase::updateOrCreate(
            ['english' => strtolower(trim($english)), 'language' => $language],
            ['native' => trim($native)]
        );

        // Clear related cache
        Cache::forget("phrase_{$language}_" . md5($english));
        
        return $phrase;
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