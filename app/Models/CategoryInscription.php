<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryInscription extends Model
{
    use HasFactory;

    protected $casts = [
        'requires_document' => 'boolean',
        'requires_voucher' => 'boolean',
        'uses_special_code' => 'boolean',
        'shows_payment' => 'boolean',
        'waives_accompanist_fee' => 'boolean',
    ];
}
