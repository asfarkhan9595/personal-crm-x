<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmFxRate extends Model
{
    use HasFactory;

    protected $table = 'crm_fx_rates';

    protected $fillable = [
        'from_currency',
        'rate',
        'fetched_on',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'fetched_on' => 'date',
    ];
}
