<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignedStudent extends Model
{
    use HasFactory;

    protected $table = "signed_students";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "id",
        "activeLecture_id"
    ];
}
