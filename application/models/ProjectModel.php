<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectModel extends Eloquent
{
    protected $table = 'projects';
    protected $fillable = ['name', 'commissioned_by', 'description', 'details', 'price', 'status', 'customer_id', 'user_id'];

    public function customer()
    {
        return $this->hasOne('CustomerModel', 'id', 'customer_id');
    }

    public function comments()
    {
        return $this->hasMany('CommentModel', 'project_id', 'id');
    }
}
