<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Summary extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->twig->display('summary/index');
    }
}
