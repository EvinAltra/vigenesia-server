<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Login extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        // Load the user model
        $this->load->model('user');
    }

    public function index_post()
    {
        $email = $this->post('email');
        $password = $this->post('password');

        // Debugging: cek email dan password yang diterima
        log_message('debug', "Email: $email");
        log_message('debug', "Password: $password");

        if (!empty($email) && !empty($password)) {
            $con['returnType'] = 'single';
            $con['conditions'] = array(
                'email' => $email,
                'is_active' => 1
            );
            $user = $this->user->getRows($con);

            // Debugging: cek hasil user yang didapatkan dari database
            if ($user) {
                log_message('debug', "User ditemukan: " . print_r($user, true));
            } else {
                log_message('debug', "User tidak ditemukan.");
            }

            if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
                $this->response([
                    'is_active' => TRUE,
                    'message' => 'User login berhasil.',
                    'data' => $user
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response("Login gagal. Silakan coba lagi.", REST_Controller::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response("Belum mengisi email dan password.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
