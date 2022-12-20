<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Signin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('form_validation');

        if ($this->session->userdata('userid')) {
            redirect('manage', 'reload');
        }

        $this->load->model(array('Basecrud_m'));
    }

    public function index()
    {
        $data = array();

        if (!empty($_POST)) {
            $this->form_validation->set_rules('username', 'User Name', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == true) {
                $username = $this->input->post('username');
                $password = md5($this->input->post('password'));

                $cek_user = $this->Basecrud_m->get_where('user',
                    array('username' => $username, 'password' => $password)
                );

                if ($cek_user->num_rows() > 0) {
                    $d = $cek_user->row();

                    $this->session->set_userdata('user_id', $d->id);
                    $this->session->set_userdata('user_username', $username);
                    $this->session->set_userdata('user_namalengkap', $d->nama_lengkap);
                    $this->session->set_userdata('user_level', $d->level);

                    if($d->level !== 'pimpinan'){
                        //kabag poduksi ?
                        $cek_produksi = $this->db->get_where('gudang',array('user_produksi' => $d->id));
                        $g = $cek_produksi->row();

                        if ($cek_produksi->num_rows() > 0) {
                          $this->session->set_userdata('user_gudang', $g->id);
                          $this->session->set_userdata('user_jabatan', 'produksi');
                        }else{
                          $cek_logistik = $this->db->get_where('gudang',array('user_logistik' => $d->id));
                          $g = $cek_logistik->row();

                          if ($cek_logistik->num_rows() > 0) {
                            $this->session->set_userdata('user_gudang', $g->id);
                            $this->session->set_userdata('user_jabatan', 'logistik');
                          }else{
                            //user tidak mempunyai jabatan, then kick her/him out !
                            $this->session->sess_destroy();
                            redirect(base_url().'signin', 'reload');
                          }
                        }
                    }

                    redirect('manage', 'reload');
                } else {
                    $data['msg'] = array(
                                    'content' => 'Username / Password Invalid',
                                    'css_class' => 'alert alert-danger',
                    );
                }
            } else {
                $data['msg'] = array(
                                'content' => validation_errors(),
                                'css_class' => 'alert alert-info',
                );
            }
        }

        $data['page_title'] = 'Please SignIn';
        $this->load->view('signin', $data);
        // var_dump($data['msg']);
    }
}
