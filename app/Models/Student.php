<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "NIM",
        "semester",
        "IPK",
        "SKS",
        "phone_number",
        'skill',
        "link_github",
        "link_porto",
        "link_linkedin",
        'judul',
        'sidang',
        'yudisium'
    ];

    public function user(){

        return $this->belongsTo(User::class, 'user_id', 'id');

    }


    public function counseling() {

        return $this->hasMany(Counseling::class, 'student_id', 'id');

    }
    public function skill() {

        return $this->hasMany(Skill::class, 'student_id', 'id');

    }

    public function experience() {

        return $this->hasMany(Experience::class, 'student_id', 'id');

    }

}