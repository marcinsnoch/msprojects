<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
    }

    public function index()
    {
        $this->twig->display('home/index');
    }
}
