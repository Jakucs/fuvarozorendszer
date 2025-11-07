<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_address',
        'delivery_address',
        'status',
        'carrier_id',
        'recipient_name',
        'recipient_phone',
    ];

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }
}
