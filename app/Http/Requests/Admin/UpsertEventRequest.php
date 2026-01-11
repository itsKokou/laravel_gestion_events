<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'venue_name' => ['required', 'string', 'max:160'],
            'venue_address' => ['required', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'min_age' => ['required', 'integer', 'min:0', 'max:120'],
            'capacity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'hero_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5MB max
            'ticket_types' => ['required', 'array', 'min:1'],
            'ticket_types.*.name' => ['required', 'string', 'max:160'],
            'ticket_types.*.price_cents' => ['required', 'integer', 'min:0'],
            'ticket_types.*.currency' => ['required', 'string', 'size:3'],
            'ticket_types.*.quantity_limit' => ['nullable', 'integer', 'min:1'],
            'ticket_types.*.sales_starts_at' => ['required', 'date'],
            'ticket_types.*.sales_ends_at' => ['required', 'date'],
            'ticket_types.*.is_active' => ['nullable', 'boolean'],
            'ticket_types.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $ticketTypes = $this->input('ticket_types', []);
            $eventId = $this->route('event')?->id;

            // Vérifier les doublons de nom et prix
            $names = [];
            $prices = [];

            foreach ($ticketTypes as $index => $ticketType) {
                // Vérifier la date de fin après la date de début
                if (isset($ticketType['sales_starts_at']) && isset($ticketType['sales_ends_at'])) {
                    $startDate = strtotime($ticketType['sales_starts_at']);
                    $endDate = strtotime($ticketType['sales_ends_at']);

                    if ($endDate <= $startDate) {
                        $validator->errors()->add(
                            "ticket_types.{$index}.sales_ends_at",
                            "La date de fin de vente doit être après la date de début de vente."
                        );
                    }
                }

                // Vérifier l'unicité du nom
                if (!empty($ticketType['name'])) {
                    $name = strtolower(trim($ticketType['name']));
                    if (isset($names[$name])) {
                        $validator->errors()->add(
                            "ticket_types.{$index}.name",
                            "Ce nom de tarif est déjà utilisé pour un autre tarif."
                        );
                    }
                    $names[$name] = $index;
                }

                // Vérifier l'unicité du prix
                if (!empty($ticketType['price_cents'])) {
                    $price = (int) $ticketType['price_cents'];
                    if (isset($prices[$price])) {
                        $validator->errors()->add(
                            "ticket_types.{$index}.price_cents",
                            "Ce prix est déjà utilisé pour un autre tarif."
                        );
                    }
                    $prices[$price] = $index;
                }
            }
        });
    }
}
