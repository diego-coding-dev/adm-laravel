<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationToken extends Model
{
    use HasFactory;

    /**
     * Declaração explícita da tabela
     *
     * @var string
     */
    protected $table = 'activation_tokens';

    /**
     * Mass assingment
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token_hash',
        'created_at'
    ];

    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
}
