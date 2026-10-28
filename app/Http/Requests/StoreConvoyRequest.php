<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreConvoyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'travel_route_id' => ['required', 'exists:travel_routes,id'],
            'event_id' => ['nullable', 'exists:events,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'departs_at' => ['required', 'date', 'after:now'],
            'capacity' => ['required', 'integer', 'min:2', 'max:200'],
        ];
    }
}
