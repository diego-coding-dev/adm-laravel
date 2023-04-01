<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUser extends Model
{

    use HasFactory;

    /**
     * 
     * @var string declaring table explicitily
     */
    protected $table = 'type_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected array $fillable = [
        'type_user'
    ];
}
