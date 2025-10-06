<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    // Laat Eloquent created_at/updated_at automatisch beheren
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'ad_id',
        'amount',
        'is_accepted',
        'pickup_date',
        'return_date',
        'return_image',
        'damage',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'is_accepted'  => 'boolean',
        'pickup_date'  => 'datetime',
        'return_date'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
