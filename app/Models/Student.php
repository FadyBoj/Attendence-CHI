<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'students';
    protected $primarykey = 'id';

    protected $fillable = [
        "id",
        "name",
        "password",
        "department",
    ];

    protected $attributes = [
        "email" => null,
    ];  

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class,'course_student');
    }
}
