<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class SummaryModel extends Eloquent
{
    protected $table = 'summaries';
    protected $fillable = ['name', 'description', 'views', 'token', 'customer_id', 'user_id'];

    public function customer()
    {
        return $this->hasOne('CustomerModel', 'id', 'customer_id');
    }

    public function user()
    {
        return $this->hasOne('UserModel', 'id', 'user_id');
    }
    
    public function projects()
    {
        return $this->belongsToMany('ProjectModel', 'project_summary', 'summary_id', 'project_id');
    }
}
