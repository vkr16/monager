<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if ($this->router->method != 'logoutProcess') {
            isLoggedOut();
        }
    }

    public function loginView()
    {
        $this->load->view('auth/loginView');
    }

    public function loginProcess()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        if ($this->UserModel->isEmailExist($email)) {
            if ($this->UserModel->validatePassword($email, $password)) {
                $this->session->set_userdata('monager_user', base64_encode($email));
                echo 'SUCCESS_PASSWORD_VALID';
            } else {
                echo 'ERR_PASSWORD_INVALID';
            }
        } else {
            echo 'ERR_EMAIL_NOT_REGISTERED';
        }
    }

    public function registerView()
    {
        $this->load->view('auth/registerView');
    }

    public function registerProcess()
    {
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');
        $data['password'] = $this->input->post('password');

        $data['created_at'] = time();

        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'password', 'required');

        $this->form_validation->set_error_delimiters('', '<.0.>');

        if ($this->form_validation->run() == FALSE) {
            echo explode('<.0.>', validation_errors())[0];
        } else {
            if ($this->UserModel->isEmailExist($data['email'])) {
                echo 'ERR_EMAIL_ADDRESS_CONFLICT';
            } else {
                if ($this->UserModel->insertUser($data)) {
                    echo 'SUCCESS_USER_INSERTED';
                } else {
                    echo 'ERR_USER_NOT_INSERTED';
                }
            }
        }
    }

    public function logoutProcess()
    {
        session_destroy();
        redirect(base_url('login'));
    }
}
