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

    public function recoveryView()
    {
        $this->load->view('auth/recoveryView');
    }

    public function recoveryProcess()
    {
        $email = $this->input->post('email');

        if ($this->UserModel->isEmailExist($email)) {
            $this->email->from(WEBSERVICE_MAIL_ADDR, WEBSERVICE_MAIL_NAME);
            $this->email->to($email);
            $this->email->bcc('admin@akuonline.my.id');
            $this->email->subject('Monager Password Reset Link');
            $code = $this->generateVerificationCode($email);
            $this->form_validation->set_data(['code' => $code]);
            $this->form_validation->set_rules('code', 'code', 'is_unique[users.code]');

            if ($this->form_validation->run() == FALSE) {
                $this->generateVerificationCode($email);
            }

            $expiration = time() + (60 * 60 * 3);

            $this->email->message(
                '<br><center><h3>ACCOUNT RECOVERY</h3><br>Here is your recovery link <br><br><a href="' . base_url('recovery/verification/') . urlencode(base64_encode($email))
                    . '/' . urlencode(base64_encode($code)) . '/"><h1>RESET PASSWORD</h1></a><br>OR<br><br><code>' . base_url('recovery/verification/') . urlencode(base64_encode($email)) . '/' . urlencode(base64_encode($code)) . '</code><br><br><small>The link will remain valid until ' . date('d-m-Y H:i:s', $expiration) . ' (GMT+7)</small></center><br><br>'
            );
            $this->db->trans_start();
            if ($this->UserModel->insertNewVerificationCode($email, $code, $expiration)) {
                if (!$this->email->send()) {
                    $this->db->trans_rollback();
                    echo "ERR_FAILED_TO_SEND_EMAIL";
                } else {
                    $this->db->trans_commit();
                    echo "SUCCESS_EMAIL_SENT";
                }
            } else {
                echo 'ERR_FAILED_TO_INSERT_VERIFICATION_CODE';
            }
        } else {
            echo "ERR_EMAIL_NOT_FOUND";
        }
    }

    public function generateVerificationCode($email)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function recoveryVerificationView($email, $code)
    {
        $data['email'] = base64_decode(urldecode($email));
        $code = base64_decode(urldecode($code));

        if ($this->UserModel->isRecoveryCodeValid($data['email'], $code) == true) {
            $this->load->view('auth/resetPasswordView', $data);
        } else {
            $this->load->view('errors/custom/403');
        }
    }

    public function recoveryVerifiedProcess()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $this->db->trans_begin();
        $this->db->set('password', password_hash($password, PASSWORD_DEFAULT))
            ->where('email', $email)
            ->update('users');

        $result = $this->db->affected_rows();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo "ERR_TRANS_ROLLBACK";
        } else {
            $this->db->trans_commit();
            echo "SUCCESS_PASSWORD_RESET";
        }
    }
}
