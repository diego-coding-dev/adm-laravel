<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TypeProduct;

class Product extends Model
{

    use HasFactory,
        SoftDeletes,
        HasFactory;

    /**
     * 
     * @var string declaring table explicitily
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_product_id',
        'description',
        'image'
    ];

    /**
     * Get the type product's description.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function description(): Attribute
    {
        return Attribute::make(
                        get: fn($value) => mb_convert_case($value, MB_CASE_TITLE),
                        set: fn($value) => mb_convert_case($value, MB_CASE_LOWER)
        );
    }

    /**
     * Get the type product that owns the product.
     */
    public function typeProduct(): object
    {
        return $this->belongsTo(TypeProduct::class, 'type_product_id');
    }

    /**
     * Get the storage product that owns the product.
     */
    public function storage(): object
    {
        return $this->hasOne(Storage::class, 'product_id', 'id');
    }

}
