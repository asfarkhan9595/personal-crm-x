<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmSyncLog extends Model
{
    use HasFactory;

    protected $table = 'crm_sync_logs';

    protected $fillable = [
        'entity_type',
        'entity_id',
        'action',
        'status',
        'error_message',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
