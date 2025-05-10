<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataPerRequest extends Model
{
    //
    protected $fillable = ['init_date', 'end_date'];

    public function ExchangeRate() : HasMany {
        return $this->hasMany(ExchangeRate::class, 'data_per_request_id', 'id');
    }
}
