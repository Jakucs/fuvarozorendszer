<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{

    use HasFactory;

    protected $table = 'transport_jobs';

    protected $fillable = [
        'pickup_address',
        'delivery_address',
        'recipient_name',
        'recipient_phone',
        'carrier_id',
    ];

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }
}
