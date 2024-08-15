<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'counseling_id',
        'lecturer_note',
        'progress',
    ];

    public function counseling()
    {
        return $this->belongsTo(Counseling::class);
    }
}
