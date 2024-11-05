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
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        // Load the user model
        $this->load->model('user');
    }

    function index_get()
    {
        $id = $this->get('iduser');
        if ($id === null || $id === '') {
            $api = $this->db->get('user')->result();
        } else {
            $this->db->where('iduser', $id);
            $api = $this->db->get('user')->result();
        }

        // Sending response with user data or message if not found
        if (!empty($api)) {
            $this->response($api, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'User not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        $email = $this->post('email');
        $password = $this->post('password');

        // Debugging: check received email and password
        log_message('debug', "Email: $email");
        log_message('debug', "Password: $password");

        if (!empty($email) && !empty($password)) {
            // Retrieve user data based on email and active status
            $con['returnType'] = 'single';
            $con['conditions'] = [
                'email' => $email,
                'is_active' => 1
            ];
            $user = $this->user->getRows($con);

            // Debugging: check user result from database
            if ($user) {
                log_message('debug', "User found: " . print_r($user, true));
            } else {
                log_message('debug', "User not found.");
            }

            // Verify password
            if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
                $this->response([
                    'is_active' => TRUE,
                    'message' => 'User login successful.',
                    'data' => $user
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Login failed. Please try again.'
                ], REST_Controller::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Email and password are required.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
