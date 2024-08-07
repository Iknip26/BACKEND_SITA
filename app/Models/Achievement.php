<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable=[
        'student_id',
        'achievement_name',
        'achievement_type',
        'achievement_level',
        'achievement_year',
        'description'
    ];

    public function Student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
