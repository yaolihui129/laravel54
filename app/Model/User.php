<?php

namespace App\Model;

// use App\Model\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
      'name','email','password'
    ];
}
