<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'front_title',
        'back_title',
        'NID',
        'max_quota',
        'phone_number',
        'isKaprodi',
        'photo_profile'
    ];

    public function user() {

        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    public function project() {

        return $this->hasMany(Project::class,'lecturer_id', 'id');

    }

    public function counseling() {

        return $this->hasMany(Counseling::class, 'counseling_id', 'id');

    }
}
