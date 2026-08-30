<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Tour extends Model {
    use HasFactory;
    protected $fillable=['name','description','tour_date','start_time','end_time','meeting_point','price','accompanist_price','capacity','status'];
    protected $casts=['tour_date'=>'date','price'=>'decimal:2','accompanist_price'=>'decimal:2'];
    public function inscriptions(){ return $this->belongsToMany(Inscription::class)->withPivot(['unit_price','has_accompanist','accompanist_price','accompanist_name','accompanist_document_type','accompanist_document_number','accompanist_phone'])->withTimestamps(); }
}
