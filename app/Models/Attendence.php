<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;

    protected $table = "attendence";
    protected $primaryKey = "id";

    protected $fillable = [
        "student_id",
        "course_id",
        "count"
    ];
}
