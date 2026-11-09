<?php

namespace App\Http\Requests;

use App\Models\CrmPayment;
use Illuminate\Foundation\Http\FormRequest;

class CrmPaymentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'paid_on' => ['required', 'date'],
            'fx_rate' => ['required', 'numeric', 'min:0.0001'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $paymentId = $this->route('payment');
            $payment = is_object($paymentId) ? $paymentId : CrmPayment::find($paymentId);

            if (!$payment || !$payment->lead) {
                return;
            }

            $lead = $payment->lead;
            $newAmount = (float) $this->input('amount', 0);
            $dealAmount = (float) $lead->deal_amount;

            // Current received excluding this payment's current amount
            $otherReceived = (float) $lead->payments()->where('id', '!=', $payment->id)->sum('amount');

            if ($dealAmount > 0 && round($otherReceived + $newAmount, 2) > round($dealAmount, 2)) {
                $maxPossible = max(0, $dealAmount - $otherReceived);
                $sym = $lead->currency_symbol;
                $validator->errors()->add(
                    'amount',
                    "Total received cannot exceed deal amount ({$sym}" . number_format($dealAmount, 2) . "). Maximum allowable amount for this payment is {$sym}" . number_format($maxPossible, 2) . "."
                );
            }
        });
    }
}
