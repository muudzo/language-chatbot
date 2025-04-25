<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phrase;
use App\Models\Conversation;

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
        
        // Save conversation
        $conversation = new Conversation();
        $conversation->user_message = $userMessage;
        $conversation->language = $language;
        $conversation->save();
        
        // Simple response logic
        $response = $this->generateResponse($userMessage, $language);
        
        // Save bot response
        $conversation->bot_response = $response;
        $conversation->save();
        
        return response()->json([
            'response' => $response,
            'language' => $language
        ]);
    }
    
    private function generateResponse($message, $language)
    {
        // Check if the message is a training input (format: "English:Native")
        if (strpos($message, ':') !== false) {
            list($english, $native) = explode(':', $message, 2);
            $this->learnPhrase(trim($english), trim($native), $language);
            return "Thank you! I've learned that \"$english\" in $language is \"$native\".";
        }
        
        // Look for an existing translation
        $phrase = Phrase::where('english', 'like', $message)
                       ->where('language', $language)
                       ->first();
        
        if ($phrase) {
            return "In $language, \"$message\" is \"$phrase->native\".";
        }
        
        // No match found
        return "I don't know how to say \"$message\" in $language yet. You can teach me by typing \"$message:native translation\".";
    }
    
    private function learnPhrase($english, $native, $language)
    {
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
        return response()->json(['language' => $language]);
    }
}