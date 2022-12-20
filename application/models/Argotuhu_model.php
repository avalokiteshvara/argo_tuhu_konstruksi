<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Argotuhu_model extends CI_Model
{
    private $gudang_id;
    private $jabatan;
    private $user_id;
    private $user_level;

    public function __construct()
    {
        parent::__construct();

        $this->gudang_id = $this->session->userdata('user_gudang');
        $this->jabatan = $this->session->userdata('user_jabatan'); //produksi atau logistik
        $this->user_id = $this->session->userdata('user_id');

        $this->user_level = $this->session->userdata('user_level');

        if (!$this->user_id) {
            redirect('signin', 'reload');
        }
    }

    public function get_permintaan($kategori = 'masuk')
    {
        if (!in_array($kategori, array('masuk', 'keluar'))) {
            redirect('manage', 'reload');
        }

        $this->db->select("a.id,a.jenis,a.tujuan_user,
                           LPAD(a.id,6,'0') AS nomor,
                           DATE_FORMAT(a.tgl,'%d-%m-%Y') AS tanggal,
                           CONCAT(b.nama,'<br/>',c.nama_lengkap) AS dari,
                           CONCAT(d.nama,'<br/>',e.nama_lengkap) AS tujuan,
                           a.status AS status_kode,
                           a.current_user,
                           f.css_class,
                           CONCAT(f.keterangan,' (',a.status,')') AS status");

        $this->db->join('gudang b', 'a.dari_gudang = b.id', 'left');
        $this->db->join('user c', 'a.dari_user = c.id', 'left');

        $this->db->join('gudang d', 'a.tujuan_gudang = d.id', 'left');
        $this->db->join('user e', 'a.tujuan_user = e.id', 'left');

        $this->db->join('tracking_status f', 'a.status = f.kode', 'left');

        // $this->db->where('a.current_user',$this->user_id);
        if($this->user_level !== 'pimpinan'){
          if ($kategori === 'masuk') {
              $this->db->where('a.tujuan_gudang', $this->gudang_id);
              // $this->db->where('a.current_user', $this->user_id);
              // $this->db->or_where('a.tujuan_user', $this->user_id);
          } else {
              $this->db->where('a.dari_gudang', $this->gudang_id);
              // $this->db->where('a.dari_user', $this->user_id);
          }
        }

        $filter_tanggal = $this->session->userdata('tgl_permintaan');
        if(isset($filter_tanggal)){
          $this->db->where('a.tgl', $filter_tanggal);
        }


        // $this->db->where('a.current_user',$this->user_id);
        $this->db->order_by('a.id DESC');
        return $this->db->get('permintaan a');

      //id,nomor,jenis,tgl,{dari gudang & user}, {ke gudang & user},status_terakhir,updated_at
    }

    public function user_produksi($gudang_id)
    {
        $this->db->select('user_produksi');
        $this->db->where('id', $gudang_id);
        $user = $this->db->get('gudang')->row();

        return $user->user_produksi;
    }

    public function user_logistik($gudang_id)
    {
        $this->db->select('user_logistik');
        $this->db->where('id', $gudang_id);
        $user = $this->db->get('gudang')->row();

        return $user->user_logistik;
    }

    public function user_pimpinan()
    {
        $this->db->select('id as pimpinan');
        $this->db->where('level', 'pimpinan');
        $user = $this->db->get('user')->row();

        return $user->pimpinan;
    }

    public function get_permintaan_detail($permintaan_id)
    {
        $rs = $this->db->query("SELECT b.kategori,
                                       b.nama AS barang,
                                       CONCAT(a.qty,' ' ,c.nama) AS jumlah,
                                       a.keterangan
                               FROM permintaan_detail a
                               LEFT JOIN barang b ON a.barang_id = b.id
                               LEFT JOIN satuan c ON b.satuan_id = c.id
                               WHERE a.permintaan_id = $permintaan_id");

        return $rs;
    }

    public function get_nomorbaru($tabel = 'permintaan')
    {
        $this->db->select('IFNULL(max(id)+1,1) AS nomor_baru');

        return $this->db->get($tabel)->row()->nomor_baru;
    }


   public function get_sirkulasi($jenis)
   {
      $rs = $this->db->query("SELECT  a.id,a.tgl,a.nomor AS nomor_sirkulasi,
                                      a.nomor_permintaan,
                                      b.nama_lengkap AS pemeriksa,
                                      a.catatan
                               FROM sirkulasi_barang a
                               LEFT JOIN user b ON a.user_pembuat = b.id
                               WHERE a.gudang_id = $this->gudang_id AND jenis = '$jenis'
                               ORDER BY a.tgl DESC");

       return $rs;
   }


  function get_sirkulasi_detail($sirkulasi_barang_id){

     $rs = $this->db->query("SELECT b.kategori,
                                    b.nama AS barang,
                                    CONCAT(a.qty,' ' ,c.nama) AS jumlah,
                                    a.keterangan
                             FROM sirkulasi_barang_detail a
                             LEFT JOIN barang b ON a.barang_id = b.id
                             LEFT JOIN satuan c ON b.satuan_id = c.id
                             WHERE a.sirkulasi_barang_id = $sirkulasi_barang_id");
     return $rs;
   }

}
