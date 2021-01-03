<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
    }

    public function index()
    {
        $this->twig->display('payments/index');
    }

    public function table_data()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $payments = PaymentModel::with('customer')->orderBy('created_at', 'desc')->get();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($payments));
    }
}
