<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
    }

    public function index()
    {
        $this->twig->display('customers/index');
    }

    public function show($id = null)
    {
        $customer = CustomerModel::with('address')->find($id);
        $this->twig->display('customers/show', compact('customer'));
    }

    public function create()
    {
        $this->twig->display('customers/create');
    }

    public function table_data()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $customers = CustomerModel::all();
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($customers));
    }
}
