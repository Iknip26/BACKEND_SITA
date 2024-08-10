<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counseling extends Model
{
    use HasFactory;
    protected $fillable = [
        "student_id",
        "lecturer_id",
        "project_id",
        "date",
        "subject",
        "description",
        "lecturer_note",
        "file",
        "progress",
        "status"
    ];

    public function student() {

        return $this->belongsTo(Student::class, 'student_id', 'id');

    }

    public function lecturer() {

        return $this->belongsTo(Lecturer::class, 'lecturer_id', 'id');

    }

    public function project() {

        return $this->belongsTo(Project::class, 'project_id', 'id');

    }
}
