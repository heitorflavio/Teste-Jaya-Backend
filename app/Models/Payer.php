<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payer extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'email',
        'entity_type',
        'type',
        'identification_type',
        'identification_number',
    ];

    public function payment()
    {
        return $this->belongsTo(Payments::class);
    }
}
