<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTravelRouteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $waypoints = $this->input('waypoints');

        if (is_string($waypoints)) {
            $this->merge(['waypoints' => json_decode($waypoints, true)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'waypoints' => ['required', 'array', 'min:2', 'max:50'],
            'waypoints.*.lat' => ['required', 'numeric', 'between:-85,85'],
            'waypoints.*.lng' => ['required', 'numeric', 'between:-180,180'],
            'waypoints.*.label' => ['nullable', 'string', 'max:80'],
        ];
    }
}
