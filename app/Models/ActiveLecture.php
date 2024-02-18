<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveLecture extends Model
{
    use HasFactory;

    protected $table = "active_lectures";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "id",
        "doctor_id",
        "uniqueId",
        "expireDate"
    ];
}
