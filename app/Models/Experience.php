<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Experience extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 
        'company', 
        'location', 
        'start_date', 
        'end_date', 
        'description', 
        'sort_order', 
        'is_current', 
        'is_published'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_published' => 'boolean',
    ];
}
