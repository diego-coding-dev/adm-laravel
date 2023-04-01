<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCart extends Model
{
    use HasFactory;

    /**
     * 
     * @var string declaring table explicitily
     */
    protected $table = 'order_carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'storage_id',
        'order_id',
        'quantity',
        'is_finished'
    ];

    /**
     * Get the order that belongs the order_carts.
     */
    public function order(): object
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
