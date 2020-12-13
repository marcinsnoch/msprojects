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

    // public function ajax_create()
    // {
    //     if (!$this->input->is_ajax_request()) {
    //         show_404();
    //     }

    //     $data = [
    //         'project_id' => $this->input->post('project_id'),
    //         'body' => $this->input->post('body'),
    //         'user_id' => $this->input->post('user_id') ?? $this->session->user_id,
    //     ];

    //     if (CommentModel::create($data)) {
    //         return $this->output->set_status_header(201);
    //     }

    //     return $this->output->set_status_header(400);
    // }

    // public function ajax_update()
    // {
    //     if (!$this->input->is_ajax_request()) {
    //         show_404();
    //     }
    //     $comment = CommentModel::find($this->input->post('comment_id'));
    //     if ($comment) {
    //         $comment->body = $this->input->post('body');
    //         $comment->user_id = $this->input->post('user_id') ?? $this->session->user_id;
    //         $comment->save();
    //         return $this->output->set_status_header(201);
    //     }
    //     return $this->output->set_status_header(400);
    // }

    // public function ajax_delete()
    // {
    //     if (!$this->input->is_ajax_request()) {
    //         show_404();
    //     }
    //     $comment = CommentModel::find($this->input->post('comment_id'));
    //     if ($comment) {
    //         $comment->delete();
    //         return $this->output->set_status_header(201);
    //     }
    //     return $this->output->set_status_header(400);
    // }

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
