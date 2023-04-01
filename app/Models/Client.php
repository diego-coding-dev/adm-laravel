<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use \Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{

    use SoftDeletes,
        HasFactory;

    /**
     * 
     * @var string declaring table explicitily
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type_user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the created_at data field.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function createdat(): Attribute
    {
        return Attribute::make(
                        get: fn($value) => date('d/m/Y', strtotime($value))
        );
    }

    /**
     * Get the orders that owns the client.
     */
    public function orders(): object
    {
        return $this->hasMany(Order::class, 'client_id', 'id');
    }

}
