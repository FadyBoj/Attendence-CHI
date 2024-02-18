<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;




class Doctor extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasUuids;

    protected $table = "doctors";
    protected $primaryKey = "id";

    protected $fillable = [
        "name",
        "password"
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class,'doctor_id');
    }
}
