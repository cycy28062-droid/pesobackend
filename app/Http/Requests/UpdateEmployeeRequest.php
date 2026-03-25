<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee')?->getKey();

        return [
            'full_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'full_name')->ignore($employeeId),
            ],
            'job_title' => ['sometimes', 'required', 'string', 'max:255'],
            'office' => ['sometimes', 'required', 'string', 'max:255'],
            'id_number' => ['prohibited'],
            'id_display' => ['prohibited'],
            'card_year' => ['prohibited'],
            'card_seq' => ['prohibited'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ];
    }
}