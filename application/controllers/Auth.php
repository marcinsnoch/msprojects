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
        // check if logged in
        $this->_logged_in();
        if ($this->input->post('login') && $this->form_validation->run('login')) {
            $user = UserModel::where('email', $this->input->post('email'))->first();
            // check if user exist
            if (!$user) {
                set_alert('danger', 'User does not exist!');
                redirect('/login');
            }
            // check if account is activated
            if ($user->token !== null) {
                // alert: account not active
                set_alert('danger', 'Your account is not activated. Please check your email.');
                redirect('/login');
            }
            // compare input password with stored user password
            if (password_verify($this->input->post('password'), $user->password)) {
                $this->_login($user);
                redirect('/home');
            }
            set_alert('danger', 'Wrong username or password!');
        }
        // correct
        //     set session
        //     update last login timestamp
        //     redirect to dashboard
        // incorrect
        //     error message
        //     check login attempts
        //         < 3
        //             message "try again"
        //         > 3
        //             login blocking 5 min.
        //             message "to many wrong login"
        $this->twig->display('auth/login');
    }
    
    /**
     * Log out from teh app
     */
    public function logout()
    {
        // session destroy
        session_destroy();
        // redirect to login page
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
            set_alert('success', 'Please check your email inbox.');
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
            set_alert('success', 'Password changed!');
            redirect('/login');
        }
        $this->twig->display('auth/recovery_password', ['token' => $user->token]);
    }
    
    /**
     * Register new user
     */
    public function register()
    {
        // validate the data
        if ($this->input->post('register') && $this->form_validation->run('register_user')) {
            // create account
            $user = UserModel::create([
                'email' => $this->input->post('email'),
                'full_name' => $this->input->post('full_name'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'token' => bin2hex(random_bytes(64)),
            ]);
            if ($user) {
                // send activation email (link with token)
                $this->load->library('Sendmail');
                $this->sendmail->activationEmail($user);
                // set flash msg. "check your email..."
                set_alert('success', 'Please check your email inbox.');
                // redirect to login page
                redirect('/login');
            }
        }
        // display register form
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
        // check token in link
        $user = UserModel::where('token', $this->input->get('token'))->first();
        if (!$user) {
            // set flash msg. "error"
            set_alert('danger', 'Some error occurred!');
        } else {
            // active account
            $user->token = null;
            $user->save();
            set_alert('success', 'Account activated successfully!');
            // email to user
            $this->load->library('Sendmail');
            $this->sendmail->confirmEmail($user);
        }
        // redirect to login
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
            redirect('/home');
        }
    }

    /**
     * Check if email exists
     */
    public function _email_exist($email)
    {
        $user_email = UserModel::where('email', $email)->first();
        if (!$user_email) {
            $this->form_validation->set_message('_email_exist', 'Email does not exist!');

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
            $this->form_validation->set_message('_email_not_exist', 'This email address is already registered');

            return false;
        }

        return true;
    }
}
