<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Payments extends Model
{
    use HasFactory;
    
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'transaction_amount',
        'installments',
        'token',
        'payment_method_id',
        'notification_url',
        'status'
    ];

    public function payer()
    {
        return $this->hasOne(Payer::class, 'payment_id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->id = Uuid::uuid4();
        });
    }

}
