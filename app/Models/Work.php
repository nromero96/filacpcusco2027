<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'whatsapp_phone',
        'current_position',
        'institution',
        'city',
        'country',
        'title',
        'area',
        'area_other',
        'abstract',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusNotes()
    {
        return $this->hasMany(WorksNote::class)->latest('id');
    }

}
