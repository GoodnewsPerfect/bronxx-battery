<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'user_id',
        'datetime',
        'status',
    ];

    protected $casts = [
        'order_id' => 'string',
        'user_id' => 'string',
        'datetime' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
