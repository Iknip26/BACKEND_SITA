<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable =[
        'student_id',
        'skill'
    ];

    public function Student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
