<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertEventRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'string', 'max:180', Rule::unique('events', 'slug')->ignore($this->route('event')?->id)],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'venue_name' => ['required', 'string', 'max:160'],
            'venue_address' => ['required', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'min_age' => ['required', 'integer', 'min:0', 'max:120'],
            'capacity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
        ];
    }
}
