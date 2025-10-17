<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    public function authorize()
    {
        // Allow all authenticated users, adjust if needed
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|string|in:Sick Leave,Causal Leave,First Half Day,Second Half Day',
            'subject' => 'required|string|max:255',
            'description' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $wordCount = str_word_count($value);
                    if ($wordCount < 30 || $wordCount > 500) {
                        $fail("The $attribute must be between 30 and 500 words. Currently $wordCount words.");
                    }
                },
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reporting_manager_email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Please select a leave type.',
            'subject.required' => 'The subject is required.',
            'description.required' => 'Please provide a description (30–500 words).',
            'start_date.required' => 'Please select a start date.',
            'end_date.required' => 'Please select an end date.',
            'end_date.after_or_equal' => 'End date must be the same or after the start date.',
            'reporting_manager_email.email' => 'Please enter a valid reporting manager email address.',
        ];
    }
}
