<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class UserModel extends Eloquent
{
    protected $table = 'users';
    protected $fillable = ['email', 'full_name', 'password', 'token'];
}
