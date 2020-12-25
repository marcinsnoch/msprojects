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
        // dump($summary->toArray());
        $this->twig->display('home/show', compact('summary'));
    }
}
