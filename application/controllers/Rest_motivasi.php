<?php
// Dokumentasi Pengerjaan Individu
// NIM : .................
// Nama : .................
// Kelas : ……………….
// Kampus : ……………….
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

use Restserver\libraries\REST_Controller;

class Rest_motivasi extends REST_Controller
{
    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
    }
    //awal fungsi operasi get
    function index_get()
    {
        // Deskripsi Fungsi :
        // ------------------------------------------------------------------
        // Script dibawah ini merupakan implementasi dari metode GET
        // -------------------------------------------------------------------
        $id = $this->get('id');
        if ($id == '') {
            $motivasi = $this->db->get('motivasi')->result();
        } else {
            $this->db->where('id', $id);
            $motivasi = $this->db->get('motivasi')->result();
        }
        $this->response($motivasi, 404);
    } //akhir fungsi operasi get
    function index_post()
    {
        $data = array(
            'id' => $this->post('id'),
            'isi_motivasi' => $this->post('isi_motivasi'),
            'iduser' => $this->post('iduser'),
            'tanggal_input' => $this->post('tanggal_input'),
            'tanggal_update' => $this->post('tanggal_update')
        );
        $insert = $this->db->insert('motivasi', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    } // akhir fungsi post

    //awal fungsi operasi put
    function index_put()
    {
        $id = $this->put('id');
        $data = array(
            'id' => $this->put('id'),
            'isi_motivasi' => $this->put('isi_motivasi'),
            'iduser' => $this->put('iduser'),
            'tanggal_input' => $this->put('tanggal_input'),
            'tanggal_update' => $this->post('tanggal_update')

        );
        $this->db->where('id', $id);
        $update = $this->db->update('motivasi', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    } //akhir fungsi put

    //awal fungsi operasi delete
    function index_delete()
    {
        $id = $this->delete('id');
        $this->db->where('id', $id);
        $delete = $this->db->delete('motivasi');
        if ($delete) {
            $this->response(array('status' => 'sukses'), 200);
        } else {
            $this->response(array('status' => 'gagal', 502));
        }
    } // akhir fungsi delete

}
