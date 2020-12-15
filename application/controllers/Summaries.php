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
        $this->twig->display('summaries/index');
    }

    public function show($id = null)
    {
        $summary = SummaryModel::with('projects')->find($id);
        $this->twig->display('summaries/show', compact('summary'));
    }
    
    public function edit($id)
    {
        
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
        return $this->output->set_status_header(200);
        } 
        return $this->output->set_status_header(400);
    }
}
