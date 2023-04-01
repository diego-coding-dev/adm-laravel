<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * 
     * @var string declaring table explicitily
     */
    protected $table = 'storages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'quantity',
        'price'
    ];

    /**
     * Get the storage product that owns the product.
     */
    public function product(): object
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
