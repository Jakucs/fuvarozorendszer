<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrier_id',
        'job_id',
        'status',
        // ha van pl. start_address, end_address, stb. azok is jöhetnek ide
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'carrier_id', 'id');
    }

}
