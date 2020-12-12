<?php

$config = [
    'login' => [
        ['field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|valid_email'],
        ['field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'],
    ],
    'user_email' => [
        ['field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|valid_email|callback__email_exist'],
    ],
    'recovery_password' => [
        ['field' => 'password', 'label' => 'Password', 'rules' => 'required|trim|min_length[8]'],
        ['field' => 'confirm_password', 'label' => 'Confirm password', 'rules' => 'required|trim|matches[password]'],
    ],
    'register_user' => [
        ['field' => 'full_name', 'label' => 'Full name', 'rules' => 'required|trim'],
        ['field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|valid_email|callback__email_not_exist'],
        ['field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'],
        ['field' => 'confirm_password', 'label' => 'Confirm password', 'rules' => 'required|trim|matches[password]'],
    ],
    'update_user' => [
        ['field' => 'full_name', 'label' => 'Full name', 'rules' => 'required|trim'],
        ['field' => 'password', 'label' => 'Password', 'rules' => 'trim'],
        ['field' => 'confirm_password', 'label' => 'Confirm password', 'rules' => 'trim|matches[password]'],
    ],
];

$config['error_prefix'] = '<div class="invalid-feedback">';
$config['error_suffix'] = '</div>';
