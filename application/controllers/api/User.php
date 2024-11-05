<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class User extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
        // Handle pre-flight requests
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    // Display user data based on user ID or all users
    public function index_get()
    {
        $id = $this->get('iduser');
        
        if ($id === null || $id === '') {
            // If no ID is provided, return all users
            $api = $this->db->get('user')->result();
        } else {
            // If an ID is provided, return the specific user
            $this->db->where('iduser', $id);
            $api = $this->db->get('user')->result();
        }

        // Send the response back to the client
        if (!empty($api)) {
            // Return user data if found
            $this->response($api, REST_Controller::HTTP_OK);
        } else {
            // Return a message if no user is found
            $this->response([
                'status' => FALSE,
                'message' => 'User not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
