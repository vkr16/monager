<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';

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

    public function recoveryView()
    {
        $this->load->view('auth/recoveryView');
    }

    public function recoveryProcess()
    {
        $email = $this->input->post('email');

        if ($this->UserModel->isEmailExist($email)) {
            try {
                $mail = new PHPMailer();

                $mail->isSMTP();
                $mail->Host       = 'mail.akuonline.my.id';
                $mail->SMTPAuth   = true;
                $mail->Username   = WEBSERVICE_MAIL_ADDR;
                $mail->Password   = WEBSERVICE_MAIL_PASSWD;
                $mail->Port       = 465;

                $mail->setFrom(WEBSERVICE_MAIL_ADDR, 'AkuOnline Web Services Team');
                $mail->addAddress($email);
                $mail->addBCC('admin@akuonline.my.id');

                $mail->isHTML(true);
                $mail->Subject = 'Monager Password Recovery Request';
                $mail->Body    = 'Email test to make sure password recovery of monager is working properly';

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo $mail->ErrorInfo;
            }
        } else {
            echo "not exists";
        }
    }
}
