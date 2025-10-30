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
        'project_name' => 'required',
        'job_name' => 'required',
        'date' => 'required|date',
        'work_description' => 'required',
        'hours' => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
    ];

    $user = auth()->user();
    $projectStartDateDepartments = [62, 68, 70, 71, 73, 85,86];

    if (in_array($user->department_id, $projectStartDateDepartments)) {
        // Add project type validation for departments that need it
        $rules['project_type'] = 'required|in:development,marketing,support,meeting';

        // Only require BA email if project type is development and project doesn't already have start date
        $rules['ba_email'] = [
            'nullable',
            'email',
            function ($attribute, $value, $fail) {
                $projectType = request('project_type');
                $projectId = request('project_name');

                // Only require BA email for development projects
                if ($projectType === 'development' && $projectId) {
                    // Check if project already has start date
                    $existingProject = \App\Models\TimeTracker::where('project_id', $projectId)
                        ->whereNotNull('project_start_date')
                        ->first();

                    if (!$existingProject && empty($value)) {
                        $fail('BA email is required for new development projects.');
                    }
                }
            },
        ];
    } else {
        $rules['ba_email'] = 'nullable|email';
        $rules['project_type'] = 'nullable';
    }

    return $rules;
}

}
