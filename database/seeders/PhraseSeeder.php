<?php

namespace Database\Seeders;

use App\Models\Phrase;
use Illuminate\Database\Seeder;

class PhraseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common Shona phrases
        $shonaData = [
            ['english' => 'hello', 'native' => 'mhoro', 'language' => 'shona', 'context' => 'greeting'],
            ['english' => 'how are you', 'native' => 'wakadini', 'language' => 'shona', 'context' => 'greeting'],
            ['english' => 'good morning', 'native' => 'mangwanani', 'language' => 'shona', 'context' => 'greeting'],
            ['english' => 'good evening', 'native' => 'manheru', 'language' => 'shona', 'context' => 'greeting'],
            ['english' => 'thank you', 'native' => 'tatenda', 'language' => 'shona', 'context' => 'gratitude'],
            ['english' => 'yes', 'native' => 'hongu', 'language' => 'shona', 'context' => 'affirmation'],
            ['english' => 'no', 'native' => 'kwete', 'language' => 'shona', 'context' => 'negation'],
            ['english' => 'please', 'native' => 'ndapota', 'language' => 'shona', 'context' => 'request'],
            ['english' => 'excuse me', 'native' => 'ndinokumbira ruregerero', 'language' => 'shona', 'context' => 'apology'],
            ['english' => 'sorry', 'native' => 'ndine urombo', 'language' => 'shona', 'context' => 'apology'],
            ['english' => 'goodbye', 'native' => 'sara zvakanaka', 'language' => 'shona', 'context' => 'farewell'],
            ['english' => 'what is your name', 'native' => 'zita rako ndiani', 'language' => 'shona', 'context' => 'introduction'],
            ['english' => 'my name is', 'native' => 'zita rangu ndi', 'language' => 'shona', 'context' => 'introduction'],
            ['english' => 'I love you', 'native' => 'ndinokuda', 'language' => 'shona', 'context' => 'affection'],
            ['english' => 'water', 'native' => 'mvura', 'language' => 'shona', 'context' => 'basic needs'],
            ['english' => 'food', 'native' => 'chikafu', 'language' => 'shona', 'context' => 'basic needs'],
        ];
        
        // Common Ndebele phrases
        $ndebeleData = [
            ['english' => 'hello', 'native' => 'sawubona', 'language' => 'ndebele', 'context' => 'greeting'],
            ['english' => 'how are you', 'native' => 'unjani', 'language' => 'ndebele', 'context' => 'greeting'],
            ['english' => 'good morning', 'native' => 'livuke njani', 'language' => 'ndebele', 'context' => 'greeting'],
            ['english' => 'good evening', 'native' => 'litshonile', 'language' => 'ndebele', 'context' => 'greeting'],
            ['english' => 'thank you', 'native' => 'ngiyabonga', 'language' => 'ndebele', 'context' => 'gratitude'],
            ['english' => 'yes', 'native' => 'yebo', 'language' => 'ndebele', 'context' => 'affirmation'],
            ['english' => 'no', 'native' => 'hatshi', 'language' => 'ndebele', 'context' => 'negation'],
            ['english' => 'please', 'native' => 'ngicela', 'language' => 'ndebele', 'context' => 'request'],
            ['english' => 'excuse me', 'native' => 'ngiyaxolisa', 'language' => 'ndebele', 'context' => 'apology'],
            ['english' => 'sorry', 'native' => 'ngiyaxolisa', 'language' => 'ndebele', 'context' => 'apology'],
            ['english' => 'goodbye', 'native' => 'hamba kahle', 'language' => 'ndebele', 'context' => 'farewell'],
            ['english' => 'what is your name', 'native' => 'ungubani ibizo lakho', 'language' => 'ndebele', 'context' => 'introduction'],
            ['english' => 'my name is', 'native' => 'ibizo lami ngingu', 'language' => 'ndebele', 'context' => 'introduction'],
            ['english' => 'I love you', 'native' => 'ngiyakuthanda', 'language' => 'ndebele', 'context' => 'affection'],
            ['english' => 'water', 'native' => 'amanzi', 'language' => 'ndebele', 'context' => 'basic needs'],
            ['english' => 'food', 'native' => 'ukudla', 'language' => 'ndebele', 'context' => 'basic needs'],
        ];
        
        // Insert data
        foreach($shonaData as $phrase) {
            Phrase::create($phrase);
        }
        
        foreach($ndebeleData as $phrase) {
            Phrase::create($phrase);
        }
    }
}