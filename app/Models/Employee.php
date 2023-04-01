<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Authenticatable
{
    use SoftDeletes;

    /**
     * 
     * @var string declaring table explicitily
     */
    protected $table = 'employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_user_id',
        'name',
        'email',
        'password',
        'is_active',
        'activate_time'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get the type product's description.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function createdat(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('d/m/Y', strtotime($value))
        );
    }
}
