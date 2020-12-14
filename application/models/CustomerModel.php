<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class CustomerModel extends Eloquent
{
    protected $table = 'customers';
    protected $fillable = ['name', 'email', 'website', 'krs', 'nip', 'regon', 'address_id'];

    public function address()
    {
        return $this->hasOne('AddressModel', 'id');
    }

    public function projects()
    {
        return $this->hasMany('ProjectModel', 'customer_id');
    }
}
