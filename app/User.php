<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens,Notifiable;
    protected $fillable = ['email', 'password','first_name','last_name','photo'];

    protected $hidden = ['password', 'remember_token'];

    public function getPhotoAttribute($value)
    {
        return ($value != '')?Url('uploads/'.$value):null;
    }


}
