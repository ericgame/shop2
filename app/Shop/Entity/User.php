<?php

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = [
        "email",
        "password",
        "type",
        "nickname",
    ];
}
