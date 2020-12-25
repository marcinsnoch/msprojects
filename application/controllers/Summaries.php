<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Summaries extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
    }

    public function index()
    {
        // dd($token = bin2hex(random_bytes(40)));
        $this->twig->display('summaries/index');
    }

    function create()
    {
        $customers = CustomerModel::get(['name', 'id']);
        if ($this->input->post('create_summary') && $this->form_validation->run('create_summary')) {
            $data = array_from_post(['name', 'description', 'token', 'customer_id']);
            $data['user_id'] = $this->session->user_id;
            SummaryModel::create($data);
            set_alert('success', lang('Created'));
            redirect('/summaries');
        }
        $this->twig->display('summaries/create', compact('customers'));
    }

    public function show($id = null)
    {
        $summary = SummaryModel::with('projects')->find($id);
        $this->twig->display('summaries/show', compact('summary'));
    }

    public function edit($id)
    {
        $customers = CustomerModel::get(['name', 'id']);
        $summary = SummaryModel::with('projects')->find($id);
        if ($this->input->post('update_summary') && $this->form_validation->run('update_summary')) {
            $summary->name = $this->input->post('name');
            $summary->description = $this->input->post('description');
            $summary->token = $this->input->post('token');
            $summary->views = $this->input->post('views');
            $summary->save();
            set_alert('success', lang('Updated'));
            redirect('/summaries/show/' . $id);
        }
        $this->twig->display('summaries/edit', compact('customers', 'summary'));
    }

    public function edit_projects($id)
    {
        $customers = CustomerModel::get(['id', 'name']);
        $summary = SummaryModel::with('projects')->find($id);
        $this->twig->display('summaries/edit_projects', compact('summary', 'customers'));
    }

    public function delete($id)
    {
        $summary = SummaryModel::find($id);
        $summary->delete();
        set_alert('success', lang('Deleted'));
        redirect('/summaries');
    }

    public function table_data()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $summaries = SummaryModel::with('customer', 'projects')->orderBy('created_at', 'desc')->get();
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($summaries));
    }

    public function table_data_projects()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = $this->input->get('summary_id');
        $summaries = SummaryModel::with('projects')->orderBy('created_at', 'desc')->find($id);
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($summaries->projects));
    }

    public function ajax_remove_project()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $summary_id = $this->input->post('summary_id');
        $ids = $this->input->post('ids');
        if ($summary_id != '' && !empty($ids)) {
            $summary = SummaryModel::find($summary_id);
            $summary->projects()->detach($ids);
            return $this->output->set_status_header(201);
        }
        return $this->output->set_status_header(400);
    }
}
