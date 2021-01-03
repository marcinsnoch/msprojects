<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->twig->display('home/index');
    }

    public function show()
    {
        $token = $this->input->get('token') ?? null;
        $summary = SummaryModel::with('projects')->where('token', $token)->first() ?? show_404();
        if ($this->new_visit()) {
            $summary->increment('views');
        }
        $this->twig->display('home/show', compact('summary'));
    }

    private function new_visit()
    {
        if (!$this->session->userdata('visit')) {
            $this->session->set_userdata(['visit' => true]);

            return true;
        }

        return false;
    }
}
