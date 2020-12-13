<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class AddressModel extends Eloquent
{
    protected $table = 'addresses';

    public function customers()
    {
        return $this->belongsTo('CustomerModel');
    }
}
