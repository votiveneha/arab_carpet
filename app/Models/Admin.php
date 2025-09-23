<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin'; // or 'admins' if that's your table name
    protected $guard = 'admin';

    protected $fillable = ['first_name','last_name', 'email_id', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
?>