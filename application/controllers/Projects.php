<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
    }

    public function index()
    {
        $customers = CustomerModel::get(['id', 'name']);
        $this->twig->display('projects/index', compact('customers'));
    }

    public function show($id = null)
    {
        $project = ProjectModel::with('customer', 'comments.user')->find($id);
        $this->twig->display('projects/show', compact('project'));
    }

    public function create()
    {
        $customers = CustomerModel::all();
        if ($this->input->post('create_project') && $this->form_validation->run('create_project')) {
            $data = array_from_post(['name', 'commissioned_by', 'description', 'details', 'price', 'customer_id']);
            $data['user_id'] = $this->session->user_id;
            ProjectModel::create($data);
            set_alert('success', lang('Created'));
            redirect('/projects');
        }
        $this->twig->display('projects/create', compact('customers'));
    }

    public function edit($id = null)
    {
        $project = ProjectModel::with('customer', 'comments.user')->find($id);
        // update
        $this->twig->display('projects/edit', compact('project'));
    }

    public function delete($id = null)
    {
        ProjectModel::destroy($id);
        set_alert('success', lang('Deleted'));
        redirect('/projects');
    }

    public function table_data()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $projects = ProjectModel::with('customer');
        if ($this->input->get('customer_id')) {
            $projects->where('customer_id', $this->input->get('customer_id'))->get();
        }
        if ($this->input->get('status')) {
            $projects->where('status', $this->input->get('status'))->get();
        }
        $projects->orderBy('created_at', 'desc');
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($projects->get()));
    }

    public function ajax_update()
    {
        $data = array_from_post(['name', 'commissioned_by', 'description', 'details', 'price', 'status', 'customer_id'], false);
        $project = ProjectModel::find($this->input->post('project_id'));
        $project->update($data);
        if ($project->save()) {
            set_alert('success', lang('Updated'));
            return $this->output->set_status_header(201);
        }
        return $this->output->set_status_header(400);
    }

    public function search_names()
    {
        $project = ProjectModel::where('name', 'like', '%'. $this->input->get('q') . '%')->get();
        echo $project->pluck('name')->toJson();
    }
}
