<?php

namespace App\Http\Requests;

use App\Models\CrmLead;
use App\Models\CrmPayment;
use Illuminate\Foundation\Http\FormRequest;

class CrmPaymentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_on' => ['nullable', 'date'],
            'fx_rate' => ['nullable', 'numeric', 'min:0.0001'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $leadId = $this->route('lead');
            if (is_object($leadId)) {
                $lead = $leadId;
            } else {
                $lead = CrmLead::find($leadId);
            }

            if (!$lead) {
                $validator->errors()->add('lead', 'Lead not found.');
                return;
            }

            $dealAmount = (float) ($lead->deal_amount ?? 0);
            if ($dealAmount <= 0) {
                $validator->errors()->add('amount', 'Cannot record payment for a deal with zero, negative, or missing deal amount.');
                return;
            }

            $newAmount = (float) $this->input('amount', 0);
            if ($newAmount <= 0) {
                $validator->errors()->add('amount', 'Payment amount must be greater than zero.');
                return;
            }

            // Calculate received amount from actual payment records in database
            $actualReceived = (float) CrmPayment::where('lead_id', $lead->id)->sum('amount');
            $remaining = round(max(0.0, $dealAmount - $actualReceived), 2);

            if (round($newAmount, 2) > $remaining) {
                $sym = $lead->currency_symbol;
                $validator->errors()->add(
                    'amount',
                    "Overpayment not allowed! Total received cannot exceed deal amount ({$sym}" . number_format($dealAmount, 2) . "). Maximum remaining payable is {$sym}" . number_format($remaining, 2) . "."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Please enter a payment amount.',
            'amount.numeric' => 'Amount must be a numeric value.',
            'amount.min' => 'Payment amount must be greater than zero.',
        ];
    }
}
