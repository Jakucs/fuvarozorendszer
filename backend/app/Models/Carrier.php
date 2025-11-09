<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Carrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transportJobs()
    {
        return $this->hasMany(TransportJob::class);
    }
}
