<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
    }

    public function index()
    {
        $user = UserModel::find($this->session->user_id);
        if ($this->input->post('update') && $this->form_validation->run('update_user')) {
            $user->full_name = $this->input->post('full_name');
            if ($this->input->post('password')) {
                $user->password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }
            $user->save();
            set_alert('success', 'Saved!');
            redirect('/profile');
        }
        $this->twig->display('profile/index', compact('user'));
    }
}
