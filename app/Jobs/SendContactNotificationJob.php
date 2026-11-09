<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;

class SendContactNotificationJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $messageData;

    public function __construct($messageData = [])
    {
        $this->messageData = $messageData;
    }

    public function handle(): void
    {
        Log::info('Processing queued contact notification job:', $this->messageData);
    }
}

