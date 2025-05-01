<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phrase;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }
    
    public function processMessage(Request $request)
    {
        $userMessage = $request->input('message');
        $language = $request->input('language', 'shona'); // Default to Shona
        
        try {
            // Save conversation
            $conversation = new Conversation();
            $conversation->user_message = $userMessage;
            $conversation->language = $language;
            $conversation->save();
            
            // Generate response
            $response = $this->generateResponse($userMessage, $language);
            
            // Update conversation with bot response
            $conversation->bot_response = $response;
            $conversation->save();
            
            return response()->json([
                'response' => $response,
                'language' => $language,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            return response()->json([
                'response' => 'Sorry, there was an error processing your request.',
                'success' => false
            ], 500);
        }
    }
    
    private function generateResponse($message, $language)
    {
        // Clean the message
        $cleanMessage = strtolower(trim($message));
        
        // Check if the message is a training input (format: "English:Native")
        if (strpos($cleanMessage, ':') !== false) {
            list($english, $native) = explode(':', $cleanMessage, 2);
            $this->learnPhrase(trim($english), trim($native), $language);
            return "Thank you! I've learned that \"$english\" in $language is \"$native\".";
        }
        
        // Check for exact match
        $phrase = Phrase::where('english', 'like', $cleanMessage)
                       ->where('language', $language)
                       ->first();
        
        if ($phrase) {
            return "In $language, \"$cleanMessage\" is \"$phrase->native\".";
        }
        
        // If no exact match, try to find a partial match
        $partialMatch = Phrase::where('english', 'like', "%$cleanMessage%")
                             ->where('language', $language)
                             ->first();
        
        if ($partialMatch) {
            return "I found something similar: In $language, \"$partialMatch->english\" is \"$partialMatch->native\".";
        }
        
        // No match found
        return "I don't know how to say \"$cleanMessage\" in $language yet. You can teach me by typing \"$cleanMessage:native translation\".";
    }
    
    private function learnPhrase($english, $native, $language)
    {
        // Clean inputs
        $english = strtolower(trim($english));
        $native = trim($native);
        
        // Check if the phrase already exists
        $phrase = Phrase::where('english', $english)
                       ->where('language', $language)
                       ->first();
                       
        if ($phrase) {
            // Update existing phrase
            $phrase->native = $native;
            $phrase->save();
        } else {
            // Create new phrase
            $phrase = new Phrase();
            $phrase->english = $english;
            $phrase->native = $native;
            $phrase->language = $language;
            $phrase->save();
        }
        
        return $phrase;
    }
    
    public function switchLanguage(Request $request)
    {
        $language = $request->input('language');
        
        // Validate language
        if (!in_array($language, ['shona', 'ndebele'])) {
            $language = 'shona'; // Default to Shona if invalid
        }
        
        return response()->json([
            'language' => $language,
            'success' => true
        ]);
    }
    
    // New method to get available phrases for a language
    public function getPhrases(Request $request)
    {
        $language = $request->input('language', 'shona');
        
        $phrases = Phrase::where('language', $language)
                        ->orderBy('english')
                        ->get(['english', 'native', 'context']);
        
        return response()->json([
            'phrases' => $phrases,
            'count' => $phrases->count(),
            'language' => $language
        ]);
    }
}