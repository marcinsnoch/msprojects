<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Comments extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
    }

    public function ajax_create()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $data = [
            'project_id' => $this->input->post('project_id'),
            'body' => $this->input->post('body'),
            'user_id' => $this->input->post('user_id') ?? $this->session->user_id,
        ];
        if (CommentModel::create($data)) {
            set_alert('success', lang('Created'));

            return $this->output->set_status_header(201);
        }

        return $this->output->set_status_header(400);
    }

    public function ajax_update()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $comment = CommentModel::find($this->input->post('comment_id'));
        if ($comment) {
            $comment->body = $this->input->post('body');
            $comment->user_id = $this->input->post('user_id') ?? $this->session->user_id;
            $comment->save();
            set_alert('success', lang('Updated'));

            return $this->output->set_status_header(201);
        }

        return $this->output->set_status_header(400);
    }

    public function ajax_delete()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $comment = CommentModel::find($this->input->post('comment_id'));
        if ($comment) {
            $comment->delete();
            set_alert('success', lang('Deleted'));

            return $this->output->set_status_header(201);
        }

        return $this->output->set_status_header(400);
    }
}
