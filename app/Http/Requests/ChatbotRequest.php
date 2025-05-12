<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatbotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return match ($this->route()->getName()) {
            'chatbot.message' => [
                'message' => ['required', 'string', 'max:500'],
                'language' => ['required', 'string', 'in:shona,ndebele'],
            ],
            'chatbot.language' => [
                'language' => ['required', 'string', 'in:shona,ndebele'],
            ],
            'chatbot.phrases' => [
                'language' => ['sometimes', 'string', 'in:shona,ndebele'],
            ],
            default => [],
        };
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Please provide a message.',
            'message.max' => 'Message is too long. Maximum 500 characters allowed.',
            'language.required' => 'Please specify a language.',
            'language.in' => 'Invalid language selected. Available options are: shona, ndebele',
        ];
    }
} 