<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'customer_ref' => ['nullable', 'string', 'max:100'],
            'delivery' => ['required', 'array'],
            'delivery.method' => ['required', 'string', 'max:50'],
            'delivery.address' => ['required', 'array'],
            'delivery.address.line1' => ['required', 'string', 'max:255'],
            'delivery.address.city' => ['required', 'string', 'max:120'],
            'delivery.address.postcode' => ['required', 'string', 'max:20'],

            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'integer', 'exists:equinox_products,id'],
            'lines.*.qty' => ['required', 'integer', 'min:1', 'max:9999'],
            'lines.*.price' => ['nullable', 'numeric', 'min:0'],

            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
