<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;




class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasUuids;
    
    protected $table = "admins";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "name",
        "password"
    ];
}
