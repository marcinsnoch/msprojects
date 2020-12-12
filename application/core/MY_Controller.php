<?php

/**
 * A base controller for CodeIgniter with view autoloading, layout support,
 * model loading, helper loading, asides/partials and per-controller 404.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-controller
 *
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */
class MY_Controller extends CI_Controller
{

    /**
     * A list of helpers to be autoloaded.
     */
    protected $helpers = [];

    /**
     * A list of Twig globals.
     */
    protected $twig_globals = [];


    /**
     * Initialize the controller, tie into the CodeIgniter superobject
     * and try to autoload the models and helpers.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->language('application');

        $this->_load_twig_globals(['config', 'session']);
        $this->_load_helpers();
    }

    /**
     * Load helpers based on the $this->helpers array.
     */
    private function _load_helpers()
    {
        foreach ($this->helpers as $helper) {
            $this->load->helper($helper);
        }
    }

    /**
     * Load Twig globals
     */
    private function _load_twig_globals($defaults = [])
    {
        // $this->twig->addGlobal('config', $this->config);
        // $this->twig->addGlobal('session', $this->session);
        $globals = array_merge($defaults, $this->twig_globals);
        foreach ($globals as $key => $val) {
            if (is_int($key)) {
                $this->twig->addGlobal($val, $this->{$val});
            } else {
                $this->twig->addGlobal($key, $val);
            }
        }
    }

    /**
     * Redirect to login page
     * if not logged in
     */
    public function logged_in()
    {
        if ((bool) !$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
}
