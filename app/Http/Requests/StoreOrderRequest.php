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
            'accountId' => ['required', 'integer'],
            'webOrderId' => ['nullable', 'string', 'max:100'],
            'reference' => ['nullable', 'string', 'max:100'],

            'shippingMethod' => ['required', 'string', 'max:50'],
            'shippingNetValue' => ['required', 'numeric', 'min:0'],
            'shippingVatCode' => ['required', 'integer'],     // Equinox expects a vat code
            'paymentMethod' => ['required', 'integer'],     // Equinox payment method id

            'customerEmail' => ['nullable', 'email', 'max:255'],
            'customerTel' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],

            'shippingAddress' => ['required', 'array'],
            'shippingAddress.name' => ['nullable', 'string', 'max:120'],
            'shippingAddress.company' => ['nullable', 'string', 'max:120'],
            'shippingAddress.address1' => ['required', 'string', 'max:255'],
            'shippingAddress.postcode' => ['required', 'string', 'max:20'],
            'shippingAddress.country' => ['nullable', 'string', 'max:120'],
            'billingAddress' => ['required', 'array'],
            'billingAddress.address1' => ['required', 'string', 'max:255'],
            'billingAddress.postcode' => ['required', 'string', 'max:20'],

            'orderLines' => ['required', 'array', 'min:1'],
            'orderLines.*.lineNumber' => ['required', 'integer', 'min:1'],
            'orderLines.*.sku' => ['required', 'string', 'max:100'],
            'orderLines.*.description' => ['nullable', 'string', 'max:255'],
            'orderLines.*.quantity' => ['required', 'integer', 'min:1', 'max:9999'],
            'orderLines.*.unitPrice' => ['required', 'numeric', 'min:0'],
            'orderLines.*.vatCode' => ['nullable', 'integer'],
        ];
    }
}
