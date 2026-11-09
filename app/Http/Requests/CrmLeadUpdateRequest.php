<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrmLeadUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $leadId = $this->route('lead') ?? $this->route('id');
        if (is_object($leadId)) {
            $leadId = $leadId->id;
        }

        return [
            'business_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('crm_leads', 'business_name')->ignore($leadId),
            ],
            'market' => ['required', 'in:India,Dubai,Other'],
            'service' => ['required', 'in:Website,Laravel,Automation'],
            'contact_channel' => ['required', 'in:WhatsApp,Instagram,Email,Call'],
            'website_url' => ['nullable', 'string', 'max:255'],
            'problem_found' => ['nullable', 'string'],
            'message_sent' => ['nullable', 'boolean'],
            'response' => ['required', 'in:No response,Interested,Not interested'],
            'follow_up_date' => ['nullable', 'date'],
            'status' => ['required', 'in:New,Contacted,Proposal,Won,Lost'],
            'currency' => ['required', 'in:INR,AED,USD'],
            'deal_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'business_name.unique' => 'A lead with this business name already exists. Duplicate business names are blocked.',
            'deal_amount.min' => 'Deal amount cannot be negative.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $leadId = $this->route('lead') ?? $this->route('id');
            if (is_object($leadId)) {
                $lead = $leadId;
            } elseif ($leadId) {
                $lead = \App\Models\CrmLead::find($leadId);
            } else {
                $lead = null;
            }

            if ($lead) {
                // Rule: If payments exist, currency change is strictly blocked
                $hasPayments = \App\Models\CrmPayment::where('lead_id', $lead->id)->exists();
                if ($hasPayments && $this->filled('currency') && strtoupper($this->input('currency')) !== strtoupper($lead->currency)) {
                    $validator->errors()->add(
                        'currency',
                        "Currency cannot be changed because this deal already has recorded payments in {$lead->currency}. To preserve accounting integrity, deal currency is permanently locked."
                    );
                }

                // Rule: Deal amount cannot be reduced below the amount already received
                $newDealAmount = (float) $this->input('deal_amount', $lead->deal_amount);
                $received = (float) \App\Models\CrmPayment::where('lead_id', $lead->id)->sum('amount');
                if ($received > 0 && round($newDealAmount, 2) < round($received, 2)) {
                    $sym = $lead->currency_symbol;
                    $validator->errors()->add(
                        'deal_amount',
                        "Deal amount cannot be reduced below the amount already received ({$sym}" . number_format($received, 2) . ")."
                    );
                }
            }
        });
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('message_sent')) {
            $this->merge([
                'message_sent' => filter_var($this->message_sent, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
