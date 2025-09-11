<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class TimeTrackerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   public function rules(): array
{
    $rules = [
        'project_name'      => 'required',
        'job_name'          => 'required',
        'date'              => 'required|date',
        'work_description'  => 'required',
        'hours'             => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
    ];

    // Only require BA email if department is in projectStartDateDepartments
    $user = auth()->user();
    $projectStartDateDepartments = [62, 68, 70, 71, 73, 85];

    if (in_array($user->department_id, $projectStartDateDepartments)) {
        $rules['ba_email'] = 'required|email';
    } else {
        $rules['ba_email'] = 'nullable|email';
    }

    return $rules;
}

}
