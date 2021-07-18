<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model

{
    use SoftDeletes;

    protected $table = 'users';
    protected $fillable = ['user_id','password','name'];
    protected $dates = ['deleted_at'];
}
