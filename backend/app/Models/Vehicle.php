<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
        public function carrier()
        {
            return $this->belongsTo(Carrier::class);
        }
}
