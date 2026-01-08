<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'customer_email' => ['required', 'email:rfc'],
            'customer_phone' => ['nullable', 'string', 'max:30'],

            'ticket_type_id' => ['required', 'integer', 'exists:ticket_types,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],

            'addons' => ['array'],
            'addons.*' => ['integer', 'exists:addons,id'],

            'attendees' => ['required', 'array', 'min:1', 'max:10'],
            'attendees.*.first_name' => ['required', 'string', 'max:80'],
            'attendees.*.last_name' => ['required', 'string', 'max:80'],
            'attendees.*.email' => ['required', 'email:rfc'],
            'attendees.*.phone' => ['nullable', 'string', 'max:30'],
            'attendees.*.birthdate' => ['required', 'date_format:Y-m-d'],

            'agree_terms' => ['required', Rule::in(['1', 1, true, 'true', 'on'])],
        ];
    }
}
