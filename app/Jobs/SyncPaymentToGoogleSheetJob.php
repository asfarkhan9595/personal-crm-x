<?php

namespace App\Jobs;

use App\Services\CrmGoogleSheetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPaymentToGoogleSheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public ?int $paymentId = null,
        public string $action = 'sync'
    ) {}

    public function handle(CrmGoogleSheetService $sheetService): void
    {
        if (!$sheetService->isConfigured()) {
            return;
        }

        // Updating payments affects both payments tab and the lead's amount_received / pending in the lead tab
        $sheetService->syncAllPayments();
        $sheetService->syncAllLeads();
    }
}
