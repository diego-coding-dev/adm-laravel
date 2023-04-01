<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;

class TypeProduct extends Model
{

    use HasFactory,
        SoftDeletes;

    /**
     * 
     * @var string declaring table explicitily
     */
    protected $table = 'type_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description'
    ];

    /**
     * Get the type product's description.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => mb_convert_case($value, MB_CASE_TITLE),
            set: fn ($value) => mb_convert_case($value, MB_CASE_LOWER)
        );
    }

    /**
     * Get the products that owns the type product.
     */
    public function product(): object
    {
        return $this->hasMany(Product::class, 'type_product_id', 'id');
    }
}
