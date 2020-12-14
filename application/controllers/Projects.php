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
        $this->twig->display('projects/index');
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

        if ($this->input->get('customer_id')) {
            $projects = ProjectModel::with('customer')
                ->orderBy('created_at', 'desc')
                ->where('customer_id', $this->input->get('customer_id'))->get();
        } else {
            $projects = ProjectModel::with('customer')->orderBy('created_at', 'desc')->get();
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($projects));
    }
}
