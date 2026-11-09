<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrmLeadStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255', 'unique:crm_leads,business_name'],
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
            'deal_amount.numeric' => 'Deal amount must be a valid number.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('message_sent')) {
            $this->merge([
                'message_sent' => filter_var($this->message_sent, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        if ($this->has('status') && $this->status === 'New Lead') {
            $this->merge(['status' => 'New']);
        }

        if ($this->deal_amount === null || $this->deal_amount === '') {
            $this->merge(['deal_amount' => 0]);
        }
    }
}
