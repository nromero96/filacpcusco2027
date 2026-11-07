<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorksNote extends Model
{
    use HasFactory;

    protected $fillable = ['work_id', 'action', 'note', 'user_id'];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
