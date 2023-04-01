<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{

    use HasFactory,
        SoftDeletes;

    /**
     *
     * @var string declaring table explicitily
     */
    protected $table = 'orders';

    /**
     * Undocumented variable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'employee_id',
        'total',
        'is_settled',
        'register'
    ];

    /**
     * Get the created_at data field.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function createdat(): Attribute
    {
        return Attribute::make(
                        get: fn($value) => date('d/m/Y', strtotime($value))
        );
    }

    /**
     * Get the client that belongs the client.
     */
    public function client(): object
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the itens on order_carts that owns the order.
     */
    public function orderCarts(): object
    {
        return $this->hasMany(OrderCart::class, 'order_id', 'id');
    }

}
