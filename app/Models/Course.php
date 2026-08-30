<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'course_date', 'start_time', 'end_time',
        'location', 'price', 'capacity', 'status',
    ];

    protected $casts = [
        'course_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function inscriptions()
    {
        return $this->belongsToMany(Inscription::class)
            ->withPivot('unit_price')
            ->withTimestamps();
    }
}
