<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


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

    public function student(): HasOne
    {
        return $this->hasOne(Student::class,'id','student_id');
    }
}
