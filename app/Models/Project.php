<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "lecturer_id",
        "title",
        "agency",
        "description",
        "tools",
        "status",
        "Approval",
        'instance'
    ];

    public function counseling() {

        return $this->hasMany(Counseling::class, 'project_id', 'id');

    }

    public function lecturer() {

        return $this->belongsTo(Lecturer::class, 'lecturer_id', 'id');

    }
}
