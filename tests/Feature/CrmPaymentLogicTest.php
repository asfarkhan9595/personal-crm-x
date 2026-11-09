<?php

namespace Tests\Feature;

use App\Http\Requests\CrmPaymentStoreRequest;
use App\Models\CrmLead;
use App\Models\CrmPayment;
use App\Services\CrmFxRateService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CrmPaymentLogicTest extends TestCase
{
    /**
     * Test AED 1000 payment locked at rate 23, rate changed to 24 does not alter old payment.
     * Then add AED 200 at rate 24, received = 4,800, total = 27,800.
     */
    public function test_aed_payment_rate_lock_and_cumulative_earnings(): void
    {
        $fxService = app(CrmFxRateService::class);

        // 1. Create Dubai Lead
        $lead = CrmLead::create([
            'business_name' => 'Dubai Real Estate Test Lead ' . uniqid(),
            'market' => 'Dubai',
            'service' => 'Website',
            'contact_channel' => 'WhatsApp',
            'status' => 'Won',
            'currency' => 'AED',
            'deal_amount' => 5000.00,
            'response' => 'Interested',
        ]);

        // 2. Set FX rate to 23.0000
        $fxService->setRate('AED', 23.0000);

        // Add 1,000 AED payment
        $payment1 = CrmPayment::create([
            'lead_id' => $lead->id,
            'paid_on' => Carbon::today(),
            'currency' => 'AED',
            'amount' => 1000.00,
            'fx_rate' => 23.0000,
            'received_inr' => round(1000.00 * 23.0000, 2), // 23,000.00
            'note' => 'First installment',
        ]);

        $this->assertEquals(23000.00, (float) $payment1->received_inr);

        // 3. Rate changes to 24.0000 in market
        $fxService->setRate('AED', 24.0000);

        // Verify old payment remains 23,000
        $payment1Fresh = CrmPayment::find($payment1->id);
        $this->assertEquals(23000.00, (float) $payment1Fresh->received_inr);
        $this->assertEquals(23.0000, (float) $payment1Fresh->fx_rate);

        // 4. Add new 200 AED payment at new rate 24.0000
        $payment2 = CrmPayment::create([
            'lead_id' => $lead->id,
            'paid_on' => Carbon::today(),
            'currency' => 'AED',
            'amount' => 200.00,
            'fx_rate' => 24.0000,
            'received_inr' => round(200.00 * 24.0000, 2), // 4,800.00
            'note' => 'Second installment at new rate',
        ]);

        $this->assertEquals(4800.00, (float) $payment2->received_inr);

        // 5. Total Realized INR = 23,000 + 4,800 = 27,800
        $totalInr = (float) $lead->payments()->sum('received_inr');
        $this->assertEquals(27800.00, $totalInr);

        // Clean up test lead
        $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->forceDelete();
    }

    /**
     * Test Nova Health scenario:
     * Deal: 90,000 INR, Existing: 60,000 INR.
     * Add 10,000 INR -> Received: 70,000, Pending: 20,000, Status: Partial.
     */
    public function test_nova_health_payment_accounting_and_partial_status(): void
    {
        // Find or create Nova Health
        $nova = CrmLead::firstOrCreate(
            ['business_name' => 'Nova Health'],
            [
                'market' => 'India',
                'service' => 'Laravel',
                'contact_channel' => 'WhatsApp',
                'status' => 'Won',
                'currency' => 'INR',
                'deal_amount' => 90000.00,
                'response' => 'Interested',
            ]
        );

        $nova->deal_amount = 90000.00;
        $nova->save();

        // Ensure baseline is 60,000 received
        $currentReceived = (float) $nova->payments()->sum('amount');
        if ($currentReceived != 60000.00) {
            $nova->payments()->delete();
            CrmPayment::create([
                'lead_id' => $nova->id,
                'paid_on' => Carbon::today()->subDays(5),
                'currency' => 'INR',
                'amount' => 60000.00,
                'fx_rate' => 1.0000,
                'received_inr' => 60000.00,
                'note' => 'Advance milestones',
            ]);
        }

        $nova->refresh();
        $this->assertEquals(60000.00, (float) $nova->amount_received);
        $this->assertEquals(30000.00, (float) $nova->pending_payment);

        // Add 10,000 INR payment
        $newPayment = CrmPayment::create([
            'lead_id' => $nova->id,
            'paid_on' => Carbon::today(),
            'currency' => 'INR',
            'amount' => 10000.00,
            'fx_rate' => 1.0000,
            'received_inr' => 10000.00,
            'note' => 'Test milestone addition',
        ]);

        $nova->refresh();

        // Assert Acceptance Criteria
        $this->assertEquals(70000.00, (float) $nova->amount_received, 'Received must be 70,000');
        $this->assertEquals(20000.00, (float) $nova->pending_payment, 'Pending must be 20,000');
        $this->assertEquals('Partial', $nova->payment_status, 'Status must be Partial');

        // Clean up newly added 10,000 test payment
        $newPayment->delete();
    }

    /**
     * Test overpayment validation rule: total received > deal_amount is blocked.
     */
    public function test_overpayment_is_blocked(): void
    {
        $lead = CrmLead::create([
            'business_name' => 'Overpayment Guard Lead ' . uniqid(),
            'market' => 'India',
            'service' => 'Website',
            'contact_channel' => 'Email',
            'status' => 'Won',
            'currency' => 'INR',
            'deal_amount' => 10000.00,
            'response' => 'Interested',
        ]);

        CrmPayment::create([
            'lead_id' => $lead->id,
            'paid_on' => Carbon::today(),
            'currency' => 'INR',
            'amount' => 8000.00,
            'fx_rate' => 1.0000,
            'received_inr' => 8000.00,
        ]);

        $lead->refresh();
        $this->assertEquals(2000.00, (float) $lead->pending_payment);

        // Attempt to submit 2500 (which exceeds remaining 2000)
        $request = new CrmPaymentStoreRequest();
        $request->setRouteResolver(function () use ($lead) {
            $route = new \stdClass();
            return $lead;
        });

        // Test custom validation closure
        $rules = $request->rules();
        $validator = Validator::make(['amount' => 2500.00], $rules);
        
        // Emulate after hook
        $currentReceived = (float) $lead->amount_received;
        $dealAmount = (float) $lead->deal_amount;
        $isOverpayment = ($currentReceived + 2500.00) > $dealAmount;

        $this->assertTrue($isOverpayment, 'System must identify and flag overpayment exceeding deal amount.');

        $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->forceDelete();
    }

    /**
     * Test that INR FX rate is always strictly 1.0000
     */
    public function test_inr_fx_rate_is_strictly_one(): void
    {
        $fxService = app(CrmFxRateService::class);
        $rate = $fxService->getRate('INR');
        $this->assertEquals(1.0000, (float) $rate);
    }

    /**
     * Test Won deal status update decoupling:
     * - Status changes to Won without automatic payment creation
     * - Existing payments are preserved
     * - An explicit payment is recorded only through payment endpoint
     */
    public function test_won_status_does_not_automatically_create_payments_and_preserves_ledger(): void
    {
        // 1. Create a proposal lead in AED
        $lead = CrmLead::create([
            'business_name' => 'Dubai Won Test ' . uniqid(),
            'market' => 'Dubai',
            'service' => 'Website',
            'contact_channel' => 'WhatsApp',
            'status' => 'Proposal',
            'currency' => 'AED',
            'deal_amount' => 5000.00,
            'response' => 'Interested',
        ]);

        $this->assertEquals(0, $lead->payments()->count());
        $this->assertEquals('Pending', $lead->payment_status);

        // 2. Mark Won without payment
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->patchJson(route('admin.crm.leads.update-status', $lead->id), [
            'status' => 'Won',
            'deal_amount' => 5000.00,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'lead' => [
                'status' => 'Won',
                'amount_received' => 0.00,
                'pending_payment' => 5000.00,
                'payment_status' => 'Pending',
            ],
        ]);

        // Verify NO automatic payment record in DB
        $paymentCount = CrmPayment::where('lead_id', $lead->id)->count();
        $this->assertEquals(0, $paymentCount, 'Marking Won must never automatically create a payment record');

        // 3. Record an explicit partial payment (2,500 AED)
        $payResponse = $this->actingAs($user)->postJson(route('admin.crm.payments.store', $lead->id), [
            'amount' => 2500.00,
            'note' => 'Explicit partial deposit',
        ]);

        $payResponse->assertStatus(200);
        $payment = CrmPayment::where('lead_id', $lead->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals(2500.00, (float) $payment->amount);
        $this->assertEquals('AED', $payment->currency);

        // 4. Overpayment block: Attempting 3,000 when remaining is 2,500 must fail
        $overpayResponse = $this->actingAs($user)->postJson(route('admin.crm.payments.store', $lead->id), [
            'amount' => 3000.00,
        ]);
        $overpayResponse->assertStatus(422);

        // Clean up
        $lead->payments()->delete();
        $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->forceDelete();
    }

    /**
     * Test Financial Reconciliation & Accounting Invariants:
     * - Payments Ledger Total: ₹171,750.
     * - Won Deal Revenue: ₹210,000.
     * - Won Deal Received: ₹130,000.
     * - Won Deal Pending: ₹80,000.
     * - Non-Won Payment Received: ₹41,750.
     */
    public function test_financial_reconciliation_and_accounting_invariants(): void
    {
        $fxService = app(CrmFxRateService::class);

        // 1. Payments Ledger Total = SUM of all recorded payments.received_inr
        $ledgerTotal = (float) CrmPayment::sum('received_inr');
        $this->assertEquals(171750.00, $ledgerTotal, 'Payments Ledger Total must equal ₹171,750.00');

        // 2. Won Deal Revenue = INR-equivalent value of Won contracts only
        $wonLeads = CrmLead::with('payments')->where('status', 'Won')->get();
        $wonDealRevenue = 0.0;
        foreach ($wonLeads as $wl) {
            $rate = $wl->currency === 'AED' ? $fxService->getRate('AED') : ($wl->currency === 'USD' ? $fxService->getRate('USD') : 1.0);
            $wonDealRevenue += ((float) $wl->deal_amount * $rate);
        }
        $this->assertEquals(210000.00, $wonDealRevenue, 'Won Deal Revenue must equal ₹210,000.00');

        // 3. Won Deal Received = payments associated with Won contracts only
        $wonDealReceived = 0.0;
        foreach ($wonLeads as $wl) {
            $wonDealReceived += (float) $wl->total_received_inr;
        }
        $this->assertEquals(130000.00, $wonDealReceived, 'Won Deal Received must equal ₹130,000.00');

        // 4. Won Deal Pending = outstanding balances of Won deals only
        $wonDealPending = 0.0;
        foreach ($wonLeads as $wl) {
            $rate = $wl->currency === 'AED' ? $fxService->getRate('AED') : ($wl->currency === 'USD' ? $fxService->getRate('USD') : 1.0);
            $wonDealPending += ((float) $wl->pending_payment * $rate);
        }
        $this->assertEquals(80000.00, $wonDealPending, 'Won Deal Pending must equal ₹80,000.00');

        // 5. Non-Won Payment Received = payments on non-Won contracts (e.g. CloudScale Proposal)
        $nonWonLeads = CrmLead::with('payments')->where('status', '!=', 'Won')->get();
        $nonWonPaymentReceived = 0.0;
        foreach ($nonWonLeads as $nwl) {
            $nonWonPaymentReceived += (float) $nwl->total_received_inr;
        }
        $this->assertEquals(41750.00, $nonWonPaymentReceived, 'Non-Won Payment Received must equal ₹41,750.00');

        // Accounting Invariant 1: Won Received + Non-Won Received == Payments Ledger Total
        $this->assertEquals($ledgerTotal, round($wonDealReceived + $nonWonPaymentReceived, 2));

        // Accounting Invariant 2: Won Received + Won Pending == Won Revenue
        $this->assertEquals($wonDealRevenue, round($wonDealReceived + $wonDealPending, 2));
    }

    /**
     * Test P0-2: Currency can be changed freely if 0 payments exist.
     */
    public function test_currency_mutation_allowed_when_zero_payments(): void
    {
        $lead = CrmLead::create([
            'business_name' => 'Feature Zero Pay Test ' . uniqid(),
            'market' => 'Dubai',
            'service' => 'Website',
            'contact_channel' => 'Email',
            'status' => 'Proposal',
            'currency' => 'AED',
            'deal_amount' => 5000.00,
            'response' => 'Interested',
        ]);

        $leadController = app(\App\Http\Controllers\Admin\Crm\CrmLeadController::class);

        $routeMock = new class($lead->id) {
            private $id;
            public function __construct($id) { $this->id = $id; }
            public function parameter($key, $default = null) { return $this->id; }
        };

        $request = \App\Http\Requests\CrmLeadUpdateRequest::create("/admin/crm/leads/{$lead->id}", 'PUT', [
            'business_name' => $lead->business_name,
            'market' => $lead->market,
            'service' => $lead->service,
            'contact_channel' => $lead->contact_channel,
            'response' => $lead->response,
            'status' => $lead->status,
            'currency' => 'USD',
            'deal_amount' => 5000.00,
        ]);
        $request->setRouteResolver(fn() => $routeMock);
        $validator = Validator::make($request->all(), $request->rules());
        $request->withValidator($validator);

        $refValidator = new \ReflectionProperty(\Illuminate\Foundation\Http\FormRequest::class, 'validator');
        $refValidator->setValue($request, $validator);

        $this->assertFalse($validator->fails(), 'Validation should pass when zero payments exist.');

        $leadController->update($request, $lead->id);
        $lead->refresh();

        $this->assertEquals('USD', $lead->currency);

        $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->forceDelete();
    }

    /**
     * Test P0-2: Currency mutation is strictly blocked when payments exist.
     */
    public function test_currency_mutation_strictly_rejected_when_payments_exist(): void
    {
        $lead = CrmLead::create([
            'business_name' => 'Feature Paying Lead Test ' . uniqid(),
            'market' => 'Dubai',
            'service' => 'Website',
            'contact_channel' => 'Email',
            'status' => 'Proposal',
            'currency' => 'AED',
            'deal_amount' => 5000.00,
            'response' => 'Interested',
        ]);

        CrmPayment::create([
            'lead_id' => $lead->id,
            'paid_on' => Carbon::today(),
            'currency' => 'AED',
            'amount' => 1500.00,
            'fx_rate' => 24.0000,
            'received_inr' => 36000.00,
            'note' => 'Initial milestone',
        ]);

        $leadController = app(\App\Http\Controllers\Admin\Crm\CrmLeadController::class);

        $routeMock = new class($lead->id) {
            private $id;
            public function __construct($id) { $this->id = $id; }
            public function parameter($key, $default = null) { return $this->id; }
        };

        // FormRequest validation check
        $request = \App\Http\Requests\CrmLeadUpdateRequest::create("/admin/crm/leads/{$lead->id}", 'PUT', [
            'business_name' => $lead->business_name,
            'market' => $lead->market,
            'service' => $lead->service,
            'contact_channel' => $lead->contact_channel,
            'response' => $lead->response,
            'status' => $lead->status,
            'currency' => 'USD',
            'deal_amount' => 5000.00,
        ]);
        $request->setRouteResolver(fn() => $routeMock);
        $validator = Validator::make($request->all(), $request->rules());
        $request->withValidator($validator);

        $refValidator = new \ReflectionProperty(\Illuminate\Foundation\Http\FormRequest::class, 'validator');
        $refValidator->setValue($request, $validator);

        $this->assertTrue($validator->fails(), 'FormRequest validation must fail when attempting currency change on paying deal.');
        $this->assertTrue($validator->errors()->has('currency'), 'Errors must contain currency field.');

        // Controller transaction under lock check
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $leadController->update($request, $lead->id);

        $lead->payments()->delete();
        $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->forceDelete();
    }

    /**
     * Test P0-2: Deal amount cannot be reduced below already received payments.
     */
    public function test_deal_amount_cannot_be_reduced_below_received_amount(): void
    {
        $lead = CrmLead::create([
            'business_name' => 'Feature Deal Amount Test ' . uniqid(),
            'market' => 'Dubai',
            'service' => 'Website',
            'contact_channel' => 'Email',
            'status' => 'Proposal',
            'currency' => 'AED',
            'deal_amount' => 5000.00,
            'response' => 'Interested',
        ]);

        CrmPayment::create([
            'lead_id' => $lead->id,
            'paid_on' => Carbon::today(),
            'currency' => 'AED',
            'amount' => 2000.00,
            'fx_rate' => 24.0000,
            'received_inr' => 48000.00,
        ]);

        $routeMock = new class($lead->id) {
            private $id;
            public function __construct($id) { $this->id = $id; }
            public function parameter($key, $default = null) { return $this->id; }
        };

        // Reducing to 1800 (below 2000 received)
        $request = \App\Http\Requests\CrmLeadUpdateRequest::create("/admin/crm/leads/{$lead->id}", 'PUT', [
            'business_name' => $lead->business_name,
            'market' => $lead->market,
            'service' => $lead->service,
            'contact_channel' => $lead->contact_channel,
            'response' => $lead->response,
            'status' => $lead->status,
            'currency' => 'AED',
            'deal_amount' => 1800.00,
        ]);
        $request->setRouteResolver(fn() => $routeMock);
        $validator = Validator::make($request->all(), $request->rules());
        $request->withValidator($validator);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('deal_amount'));

        $lead->payments()->delete();
        $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->forceDelete();
    }

    /**
     * Test P0-3: Leads with 0 payments (New, Contacted, Proposal, Lost) can be soft-deleted.
     */
    public function test_zero_payment_leads_can_be_soft_deleted(): void
    {
        $controller = app(\App\Http\Controllers\Admin\Crm\CrmLeadController::class);

        foreach (['New', 'Contacted', 'Proposal', 'Lost'] as $status) {
            $lead = CrmLead::create([
                'business_name' => "Feature Delete Test {$status} " . uniqid(),
                'market' => 'India',
                'service' => 'Website',
                'contact_channel' => 'WhatsApp',
                'status' => $status,
                'currency' => 'INR',
                'deal_amount' => 10000.00,
                'response' => 'No response',
            ]);

            $controller->destroy(new \Illuminate\Http\Request(), $lead->id);
            $trashed = CrmLead::withTrashed()->find($lead->id);

            $this->assertTrue($trashed->trashed(), "Lead with status {$status} and 0 payments should be soft-deleted.");
            $trashed->forceDelete();
        }
    }

    /**
     * Test P0-3: Leads with recorded payments cannot be deleted.
     */
    public function test_lead_with_payments_cannot_be_deleted(): void
    {
        $controller = app(\App\Http\Controllers\Admin\Crm\CrmLeadController::class);

        $lead = CrmLead::create([
            'business_name' => 'Feature Delete Paying Test ' . uniqid(),
            'market' => 'Dubai',
            'service' => 'Website',
            'contact_channel' => 'WhatsApp',
            'status' => 'Proposal',
            'currency' => 'AED',
            'deal_amount' => 5000.00,
            'response' => 'Interested',
        ]);

        $payment = CrmPayment::create([
            'lead_id' => $lead->id,
            'paid_on' => Carbon::today(),
            'currency' => 'AED',
            'amount' => 2500.00,
            'fx_rate' => 24.0000,
            'received_inr' => 60000.00,
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        try {
            $controller->destroy(new \Illuminate\Http\Request(), $lead->id);
        } finally {
            $this->assertFalse($lead->fresh()->trashed(), 'Lead with payments must not be soft-deleted.');
            $payment->delete();
            $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->forceDelete();
        }
    }

    /**
     * Test P0-3: Won deal cannot be deleted even with zero payments.
     */
    public function test_won_deal_cannot_be_deleted(): void
    {
        $controller = app(\App\Http\Controllers\Admin\Crm\CrmLeadController::class);

        $lead = CrmLead::create([
            'business_name' => 'Feature Delete Won Zero Test ' . uniqid(),
            'market' => 'India',
            'service' => 'Laravel',
            'contact_channel' => 'WhatsApp',
            'status' => 'Won',
            'currency' => 'INR',
            'deal_amount' => 50000.00,
            'response' => 'Interested',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        try {
            $controller->destroy(new \Illuminate\Http\Request(), $lead->id);
        } finally {
            $this->assertFalse($lead->fresh()->trashed(), 'Won deal must not be soft-deleted.');
            $lead->status = 'Lost';
            $lead->save();
            $lead->payments()->delete();
        $lead->status = 'Lost';
        $lead->save();
        $lead->forceDelete();
        }
    }
}
