<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
    }

    public function index()
    {
        $users = UserModel::all();
        $this->twig->display('users/index', compact('users'));
    }
}
