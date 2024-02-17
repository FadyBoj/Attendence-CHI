<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;



class Student extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasUuids;

    protected $table = 'students';
    protected $primarykey = 'id';

    protected $fillable = [
        "college_id",
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
