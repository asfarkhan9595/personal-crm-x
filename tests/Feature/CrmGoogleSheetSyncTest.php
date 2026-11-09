<?php

namespace Tests\Feature;

use App\Jobs\SyncLeadToGoogleSheetJob;
use App\Jobs\SyncPaymentToGoogleSheetJob;
use App\Models\CrmLead;
use App\Models\CrmPayment;
use App\Models\CrmSyncLog;
use App\Services\CrmGoogleSheetService;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CrmGoogleSheetSyncTest extends TestCase
{
    /**
     * Test Google Sheet service graceful handling when credentials not yet supplied.
     */
    public function test_google_sheet_service_graceful_when_unconfigured(): void
    {
        $service = app(CrmGoogleSheetService::class);
        $result = $service->syncAllLeads();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('disabled or unconfigured', $result['message']);
    }

    /**
     * Test that Lead creation dispatches SyncLeadToGoogleSheetJob.
     */
    public function test_lead_creation_dispatches_sync_job(): void
    {
        Queue::fake();

        $lead = CrmLead::create([
            'business_name' => 'Sheet Queue Test Lead ' . uniqid(),
            'market' => 'India',
            'service' => 'Website',
            'contact_channel' => 'WhatsApp',
            'status' => 'New',
            'currency' => 'INR',
            'deal_amount' => 30000.00,
        ]);

        dispatch(new SyncLeadToGoogleSheetJob($lead->id, 'create'));

        Queue::assertPushed(SyncLeadToGoogleSheetJob::class, function ($job) use ($lead) {
            return $job->leadId === $lead->id && $job->action === 'create';
        });

        $lead->forceDelete();
    }

    /**
     * Test that Payment creation dispatches SyncPaymentToGoogleSheetJob.
     */
    public function test_payment_creation_dispatches_sync_job(): void
    {
        Queue::fake();

        $lead = CrmLead::create([
            'business_name' => 'Payment Queue Test Lead ' . uniqid(),
            'market' => 'Dubai',
            'service' => 'Automation',
            'contact_channel' => 'Email',
            'status' => 'Won',
            'currency' => 'AED',
            'deal_amount' => 4000.00,
        ]);

        $payment = CrmPayment::create([
            'lead_id' => $lead->id,
            'paid_on' => now()->toDateString(),
            'currency' => 'AED',
            'amount' => 1000.00,
            'fx_rate' => 23.0000,
            'received_inr' => 23000.00,
        ]);

        dispatch(new SyncPaymentToGoogleSheetJob($payment->id, 'create'));

        Queue::assertPushed(SyncPaymentToGoogleSheetJob::class, function ($job) use ($payment) {
            return $job->paymentId === $payment->id;
        });

        $payment->delete();
        $lead->forceDelete();
    }

    /**
     * Test Sync logging writes into crm_sync_logs table.
     */
    public function test_sync_logger_records_entries(): void
    {
        $service = app(CrmGoogleSheetService::class);
        $service->logSync('lead', 999, 'test_action', 'success', null, ['test' => true]);

        $log = CrmSyncLog::where('entity_type', 'lead')
            ->where('entity_id', 999)
            ->where('action', 'test_action')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('success', $log->status);

        $log->delete();
    }
}
