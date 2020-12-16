<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Log in in to app
     */
    public function login()
    {
        // TODO: create attempt checking system
        $this->_logged_in();
        if ($this->input->post('login') && $this->form_validation->run('login')) {
            $user = UserModel::where('email', $this->input->post('email'))->first();
            if ($user->token !== null) {
                set_alert('danger', lang('Your_account_is_not_activated'));
                redirect('/login');
            }
            if ($user->role == 'tmp') {
                set_alert('warning', lang('Your_account_is_still_being_verified'));
                redirect('/login');
            }
            if (password_verify($this->input->post('password'), $user->password)) {
                $this->_login($user);
                redirect('/dashboard');
            }
            set_alert('danger', lang('Wrong_username_or_password'));
        }
        $this->twig->display('auth/login');
    }

    /**
     * Log out from teh app
     */
    public function logout()
    {
        session_destroy();
        redirect('/login');
    }

    /**
     * Forgot the password
     */
    public function forgot_password()
    {
        if ($this->input->post('send') && $this->form_validation->run('user_email')) {
            $user = UserModel::where('email', $this->input->post('email'))->first();
            $user->token = bin2hex(random_bytes(64));
            $user->save();
            $this->load->library('Sendmail');
            $this->sendmail->resetPasswordEmail($user);
            set_alert('success', lang('Please_check_your_email_inbox'));
            redirect('/login');
        }
        $this->twig->display('auth/forgot_password');
    }

    /**
     * Reset forgot password
     */
    public function recovery_password()
    {
        $user = UserModel::where('token', $this->input->get('token'))->first() ?? show_404();
        if ($this->input->post('recovery') && $this->form_validation->run('recovery_password')) {
            $user->password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $user->token = null;
            $user->save();
            set_alert('success', lang('Password_changed'));
            redirect('/login');
        }
        $this->twig->display('auth/recovery_password', ['token' => $user->token]);
    }

    /**
     * Register new user
     */
    public function register()
    {
        if ($this->input->post('register') && $this->form_validation->run('register_user')) {
            $user = UserModel::create([
                        'email' => $this->input->post('email'),
                        'full_name' => $this->input->post('full_name'),
                        'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                        'token' => bin2hex(random_bytes(64)),
                        'role' => 'tmp',
            ]);
            if ($user) {
                $this->load->library('Sendmail');
                $this->sendmail->activationEmail($user);
                set_alert('success', lang('Please_check_your_email_inbox'));
                redirect('/login');
            }
        }
        $this->twig->display('auth/register');
    }

    /**
     * Activate the created account
     */
    public function activation()
    {
        if (!$this->input->get('token')) {
            show_404();
        }
        $user = UserModel::where('token', $this->input->get('token'))->first();
        if (!$user) {
            set_alert('danger', lang('Some_error_occurred'));
        } else {
            $user->token = null;
            $user->save();
            set_alert('success', lang('Account_activated_successfully'));
            $this->load->library('Sendmail');
            $this->sendmail->confirmEmail($user);
        }
        redirect('login');
    }
    // Private methods

    /**
     * Login user
     */
    public function _login($user)
    {
        $user_data = [
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'logged_in' => true,
        ];
        $this->session->set_userdata($user_data);
    }

    /**
     * Check if is logged in
     */
    public function _logged_in()
    {
        if ((bool) $this->session->userdata('logged_in')) {
            redirect('/dashboard');
        }
    }

    /**
     * Check if email exists
     */
    public function _email_exist($email)
    {
        $user_email = UserModel::where('email', $email)->first();
        if (!$user_email) {
            $this->form_validation->set_message('_email_exist', lang('Email_does_not_exist'));

            return false;
        }

        return true;
    }

    /**
     * Check if email has been already used
     */
    public function _email_not_exist($email)
    {
        $user_email = UserModel::where('email', $email)->first();
        if ($user_email) {
            $this->form_validation->set_message('_email_not_exist', lang('This_email_address_is_already_registered'));

            return false;
        }

        return true;
    }
}
