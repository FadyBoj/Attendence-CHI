<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Course extends Model
{
    use HasFactory;

    protected $table = "courses";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillabe = [
        "name",
        "doctor_id",
    ];
}
