<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class CommentModel extends Eloquent
{
    protected $table = 'comments';
    protected $fillable = ['project_id', 'body', 'user_id'];

    public function user()
    {
        return $this->hasOne('UserModel', 'id', 'user_id');
    }
}
