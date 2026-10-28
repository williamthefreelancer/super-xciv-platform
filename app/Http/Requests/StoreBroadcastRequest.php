<?php

namespace App\Http\Requests;

use App\Models\Broadcast;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBroadcastRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', Rule::in(array_keys(Broadcast::TYPES))],
            'stream_url' => ['nullable', 'url:http,https', 'max:500'],
            'scheduled_at' => ['nullable', 'date'],
            'event_id' => ['nullable', Rule::exists('events', 'id')->where('host_id', $this->user()->id)],
        ];
    }
}
