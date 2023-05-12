<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        if (isset($_SESSION['monager_user'])) {
            $CI = &get_instance();
            $email = base64_decode($_SESSION['monager_user']);
            $CI->load->model('UserModel');
            if (!$CI->UserModel->isEmailExist($email)) {
                redirect(base_url('logout'));
            }
        } else {
            redirect(base_url('logout'));
        }
    }
}

if (!function_exists('isLoggedOut')) {
    function isLoggedOut()
    {
        if (isset($_SESSION['monager_user'])) {
            $CI = &get_instance();
            $email = base64_decode($_SESSION['monager_user']);
            $CI->load->model('UserModel');
            if ($CI->UserModel->isEmailExist($email)) {
                redirect(base_url('budget'));
            }
        }
    }
}
