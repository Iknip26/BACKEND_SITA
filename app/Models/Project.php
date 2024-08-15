<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "lecturer1_id",
        'lecturer2_id',
        'student_id',
        "title",
        "agency",
        "description",
        "tools",
        "status",
        "Approval_lecturer_1",
        "Approval_lecturer_2",
        "Approval_kaprodi",
        'instance',
        'year',
        'uploadedBy'
    ];

    public function counseling() {

        return $this->hasMany(Counseling::class, 'project_id', 'id');

    }

    public function lecturer1() {

        return $this->belongsTo(Lecturer::class, 'lecturer1_id', 'id');

    }
    public function lecturer2() {

        return $this->belongsTo(Lecturer::class, 'lecturer2_id', 'id');

    }

    public function student() {

        return $this->belongsTo(Student::class,'student_id','id');

    }
}
