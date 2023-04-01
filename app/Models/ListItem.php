<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{

    use HasFactory;

    /**
     * 
     * @var string Declare table explicitly
     */
    protected $table = 'list_itens';

    /**
     * 
     * @var array Mass assignment
     */
    protected $fillable = [
        'storage_id',
        'order_id',
        'quantity'
    ];

}
