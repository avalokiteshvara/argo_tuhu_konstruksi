<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Basecrud_m extends CI_Model
{
    public $limit;
    public $offset;
    public $sort;
    public $order;
    //public $tbl_name;

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($tbl_name, $data)
    {
        $this->db->insert($tbl_name, $data);
        $query = $this->db->query('SELECT LAST_INSERT_ID()');
        $row = $query->row_array();
        return $row['LAST_INSERT_ID()'];
    }

    public function update($tbl_name, $id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($tbl_name, $data);
    }

    public function delete($tbl_name, $data)
    {
        $this->db->delete($tbl_name, $data);
    }

    public function get_where($tblname, $data, $sort = null, $order = null)
    {
        if ($sort != null && $order != null) {
            $this->db->order_by("$sort", "$order");
        }

        $rs = $this->db->get_where($tblname, $data);

        return $rs;
    }

    public function get_random($tblname, $limit = 666)
    {
        $this->db->order_by('RAND()');
        $rs = $this->db->get($tblname, $limit);

        return $rs;
    }

    public function get($tblname, $limit = 666, $sort = null, $order = null)
    {
        if ($sort != null && $order != null) {
            $this->db->order_by("$sort", "$order");
        }

        $rs = $this->db->get($tblname, $limit);

        return $rs;
    }

    /*
    function change_password($old_pass,$new_pass){

        $id =  $this->session->userdata('userid');

        $this->db->where('userpass',md5($old_pass));
        $this->db->where('id',  $id);
        $query = $this->db->get('tbl_user');

        if ($query->num_rows() > 0){
            $this->db->flush_cache();

            $data['userpass'] = md5($new_pass);
            $this->db->where('id', $id);
            $this->db->update('tbl_user', $data);

            return TRUE;
        }else{
            return FALSE;
        }
    }
    */
}
