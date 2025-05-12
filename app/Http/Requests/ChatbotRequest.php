<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatbotRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message' => 'required|string|max:500',
            'language' => 'required|string|in:shona,ndebele'
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'Please provide a message.',
            'message.max' => 'Message is too long. Maximum 500 characters allowed.',
            'language.in' => 'Invalid language selected. Available options are: shona, ndebele'
        ];
    }
} 