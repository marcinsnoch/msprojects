<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class PaymentModel extends Eloquent
{
    protected $table = 'payments';
    protected $fillable = ['name', 'description', 'amount', 'customer_id', 'user_id'];

    public function customer()
    {
        return $this->hasOne('CustomerModel', 'id', 'customer_id');
    }

    public function user()
    {
        return $this->hasOne('UserModel', 'id', 'user_id');
    }
}
