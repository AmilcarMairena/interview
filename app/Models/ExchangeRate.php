<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRate extends Model
{
    //
    protected $fillable = ['date', 'er_sale', 'er_purchase', 'data_per_request_id'];

    public function DataPerRequest() : BelongsTo {
        return $this->belongsTo(DataPerRequest::class, 'data_per_request_id', 'id');
    }
}
