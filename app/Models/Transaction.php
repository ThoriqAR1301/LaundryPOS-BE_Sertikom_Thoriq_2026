<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_code',
        'admin_id',
        'customer_id',
        'service_id',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'paid_at',
        'cloth_photo',
    ];

    protected $casts = [
        'paid_at'    => 'datetime',
        'deleted_at' => 'datetime',
        'total_price'=> 'float',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function isDeletable(): bool
    {
        if ($this->payment_method === 'cash') {
            return $this->status === 'diambil';
        }

        if ($this->payment_method === 'transfer') {
            return $this->status === 'diambil' && !empty($this->payment_proof);
        }

        return false;
    }
}