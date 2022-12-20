<?php
/*

$states = array(
    0    => 'unknown',
    1    => 'list',
    2    => 'add',
    3    => 'edit',
    4    => 'delete',
    5    => 'insert',
    6    => 'update',
    7    => 'ajax_list',
    8    => 'ajax_list_info',
    9    => 'insert_validation',
    10    => 'update_validation',
    11    => 'upload_file',
    12    => 'delete_file',
    13    => 'ajax_relation',
    14    => 'ajax_relation_n_n',
    15    => 'success',
    16    => 'export',
    17    => 'print'
    );
*/

defined('BASEPATH') or exit('No direct script access allowed');

class Manage extends CI_Controller
{
    private $user_level;
    private $gudang_id;
    private $jabatan;
    private $user_id;

    private $current_user_produksi;
    private $current_user_logistik;
    private $current_user_pimpinan;

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->model(array('Basecrud_m', 'Argotuhu_model'));

        $this->user_level = $this->session->userdata('user_level');

        if($this->user_level !== 'pimpinan'){
          $this->gudang_id = $this->session->userdata('user_gudang');
          $this->jabatan = $this->session->userdata('user_jabatan'); //produksi atau logistik
        }

        $this->user_id = $this->session->userdata('user_id');

        if($this->user_level !== 'pimpinan'){
          $this->current_user_produksi = $this->Argotuhu_model->user_produksi($this->gudang_id);
          $this->current_user_logistik = $this->Argotuhu_model->user_logistik($this->gudang_id);
        }

        $this->current_user_pimpinan = $this->Argotuhu_model->user_pimpinan();

        if (!$this->session->userdata('user_username')) {
            redirect(base_url(), 'reload'); //go back kids!
        }

        $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    }

    public function _page_output($array = null)
    {
        // $array['active_menu'] = "";
        $this->load->view('manage.php', $array);
    }

    public function index()
    {
        $data['page_name'] = 'beranda';
        $this->_page_output($data);
    }


    public function gudang()
    {
        try {
            $crud = new Grocery_CRUD();

            $crud->set_table('gudang');
            $crud->set_subject('Data Gudang');
            $crud->order_by('nama', 'ASC');

            $crud->columns('nama', 'lokasi', 'user_produksi', 'user_logistik');

            $crud->display_as('nama', 'Nama Gudang');
            $crud->display_as('user_produksi', 'Kabag Produksi');
            $crud->display_as('user_logistik', 'Kabag Logistik');

            $crud->required_fields('nama', 'lokasi', 'user_produksi', 'user_logistik');
            $crud->field_type('updated_at', 'hidden');

            $crud->set_relation('user_produksi', 'user', 'nama_lengkap', array('level' => 'kabag'));
            $crud->set_relation('user_logistik', 'user', 'nama_lengkap', array('level' => 'kabag'));

            $output = $crud->render();

            $this->_page_output($output);
        } catch (Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    function get_qty_pengembalian($permintaan_id,$barang_id){

      $total_kembali = 0;
      $id_gudang_pemberi = $this->db->get_where('permintaan',array('id' => $permintaan_id))->row()->tujuan_gudang;
      // echo $id_gudang_pemberi;
      // exit(0);
      $b =  $this->db->get_where(
            'sirkulasi_barang',
            array('nomor_permintaan' => $permintaan_id,
                  'pengembalian' => 'Y',
                  'jenis' => 'masuk',
                  'gudang_id' => $id_gudang_pemberi)
      );

      foreach ($b->result_array() as $det_b) {
        $sirkulasi_barang_id = $det_b['id'];

        $jml = $this->db->query("SELECT SUM(qty) AS jml
                                 FROM sirkulasi_barang_detail
                                 WHERE sirkulasi_barang_id = $sirkulasi_barang_id
                                        AND barang_id = $barang_id")->row()->jml;

        $total_kembali += $jml;


      }

      return $total_kembali;


    }

    function sirkulasi_barang($jenis = 'masuk', $cmd = null, $param = null,$param2 = null)
    {
        $this->load->library('SesItem');
        //$this->sesitem->destroy();

        if ($cmd === 'detail') {
            $sirkulasi_barang_id = $_GET['id'];
            $detail = $this->Argotuhu_model->get_sirkulasi_detail($sirkulasi_barang_id);

            $row_table = '';
            if ($detail->num_rows() > 0) {
                foreach ($detail->result_array() as $row) {
                    $row_table .= '<tr>
                                    <td>'.ucwords($row['kategori']).'</td>
                                    <td>'.$row['barang'].'</td>
                                    <td>'.ucwords($row['jumlah']).'</td>
                                    <td>'.$row['keterangan'].'</td>
                                  </tr>';
                }
            } else {
                $row_table .= '<tr>
                                <td colspan="4" style="text-align: center; font-weight: bold">
                                   <div class="alert alert-danger" style="margin-bottom: 0px">Data barang tidak ada</div>
                                </td>
                             </tr>';
            }

            echo $row_table;
        } elseif ($cmd === 'add-detail') {

            // $this->sesitem->insert(array('id' => uniqid(), 'nama' => 'test'));
            // $this->sesitem->insert(array('id' => uniqid(), 'nama' => 'test'));

            if (!empty($_POST)) {
                $barang_id = $this->input->post('barang_id');
                $qty = $this->input->post('qty');
                $keterangan = $this->input->post('keterangan');

                $this->sesitem->insert(array(
                                           'id' => uniqid(),
                                           'barang_id' => $barang_id,
                                           'qty' => $qty,
                                           'keterangan' => $keterangan, )
                                );
            }

            $table_row = '';

            foreach ($this->sesitem->contents() as $item) {
                $barang = $this->db->get_where('barang', array('id' => $item['barang_id']))->row();
                $satuan = $this->db->get_where('satuan', array('id' => $barang->satuan_id))->row();

                $nama = $barang->nama;
                $kategori = ucwords($barang->kategori);
                $nama_satuan = $satuan->nama;

                $table_row .= "<tr id='row_".$item['rowid']."'>
                                <td>".$kategori.'</td>
                                <td>'.$nama.'</td>
                                <td>'.$item['qty'].' '.$nama_satuan.'</td>
                                <td>'.$item['keterangan']."</td>
                                <td>
                                  <div class=\"btn-group\">
                                     <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"del('".$item['rowid']."')\"><i class=\"fa fa-trash-o\"></i> Hapus</button>
                                  </div>
                                </td>
                              </tr>";
            }

            echo $table_row;
        } elseif ($cmd === 'del-detail') {
            $this->sesitem->remove(array('rowid' => $param));
            echo count($this->sesitem->contents());
        } elseif ($cmd === 'add') {


            $data['nomor_baru'] = $this->Argotuhu_model->get_nomorbaru('sirkulasi_barang');
            $data['page_name'] = 'f_sirkulasi_barang';

            $this->sesitem->destroy();
            // echo count($this->sesitem->contents());


            if(intval($param) > 0){
              $int_nomor_permintaan = intval($param);
              //hapus record sebelumnya di session


              $permintaan = $this->db->get_where('permintaan_detail',array('permintaan_id' => $int_nomor_permintaan));
              foreach ($permintaan->result_array() as $row) {

                //apakah ini pengembalian ? jika ya maka hanya terima jenis alat saja

                if($param2 === 'Y'){
                  //jika bahan maka continue
                  $cek = $this->db->get_where('barang',array('id' => $row['barang_id']));
                  if($cek->row()->kategori === 'bahan'){
                    continue;
                  }
                }

                $qty_pengembalian = $this->get_qty_pengembalian($int_nomor_permintaan,$row['barang_id']);

                $this->sesitem->insert(


                              array(
                                   'id' => uniqid(),
                                   'barang_id' => $row['barang_id'],
                                   'qty' => ($row['qty'] - $qty_pengembalian),
                                   'keterangan' => $row['keterangan']
                              )
                        );

              }

            }

            $this->_page_output($data);
        } elseif ($cmd === 'add_act') {


            //detail ngga ada


            // $array['page_name'] = 'f_sirkulasi_barang';
            // $array['nomor_baru'] = $this->Argotuhu_model->get_nomorbaru('sirkulasi_barang');
            // $this->_page_output($array);

            $this->form_validation->set_rules('nomor', 'Nomor Sirkulasi', 'required|is_unique[sirkulasi_barang.nomor]');
            $this->form_validation->set_rules('nomor_permintaan', 'Nomor Permintaan', 'required');

            if ($this->form_validation->run() == true)
            {
                if(count($this->sesitem->contents()) == 0){

                  $array['msg'] = array(
                                  'content'   => 'Detail barang masih kosong!',
                                  'css_class' => 'alert alert-danger',
                  );

                  $array['page_name'] = 'f_sirkulasi_barang';
                  $array['nomor_baru'] = $this->Argotuhu_model->get_nomorbaru('sirkulasi_barang');
                  $this->_page_output($array);

                }else{

                  $nomor = $this->input->post('nomor');
                  $nomor_permintaan = $this->input->post('nomor_permintaan');
                  $gudang_id = $this->session->userdata('user_gudang');
                  $user_pembuat = $this->session->userdata('user_id');
                  $catatan = $this->session->userdata('catatan');

                  //apakah sirkulasi ini adalah barang pengembalian ?
                  $pengembalian = $this->input->post('pengembalian');

                  $head = array(
                            'gudang_id' => $gudang_id,
                            'jenis' => $jenis,
                            'nomor' => $nomor,
                            'nomor_permintaan' => $nomor_permintaan,
                            'user_pembuat' => $user_pembuat,
                            'pengembalian' => $pengembalian,
                            'catatan' => $catatan,
                          );

                  $sirkulasi_barang_id = $this->Basecrud_m->insert('sirkulasi_barang', $head);

                  foreach ($this->sesitem->contents() as $item) {
                      $this->Basecrud_m->insert('sirkulasi_barang_detail',
                        array(
                          'sirkulasi_barang_id' => $sirkulasi_barang_id,
                          'barang_id' => $item['barang_id'],
                          'qty' => $item['qty'],
                          'keterangan' => $item['keterangan'],
                        )
                    );
                  }

                  $this->sesitem->destroy();
                  redirect('manage/sirkulasi-barang/'.$jenis, 'reload');
                }


            } else {

                if(count($this->sesitem->contents()) == 0){

                  $array['msg'] = array(
                                  'content'   => 'Detail barang masih kosong!',
                                  'css_class' => 'alert alert-danger',
                  );

                }else{

                  $array['msg'] = array(
                                'content' => validation_errors(),
                                'css_class' => 'alert alert-info',
                  );

                }



                $array['page_name'] = 'f_sirkulasi_barang';
                $array['nomor_baru'] = $this->Argotuhu_model->get_nomorbaru('sirkulasi_barang');
                $this->_page_output($array);

            }



        } else {
            $data['jenis'] = $jenis;
            $data['page_name'] = 'l_sirkulasi_barang';
            $data['recordset'] = $this->Argotuhu_model->get_sirkulasi($jenis);

            $this->_page_output($data);
        }
    }

    public function stok_opname()
    {
        try {
            $crud = new Grocery_CRUD();

            $crud->set_table('stok_opname');
            $crud->where('gudang_id',$this->gudang_id);
            $crud->order_by('updated_at ASC');
            $crud->set_subject('Stok Opname');
            $crud->order_by('tgl', 'ASC');

            $crud->columns('tgl', 'user_id', 'barang_id', 'qty');

            $crud->display_as('tgl', 'Tanggal');
            $crud->display_as('user_id', 'User');
            $crud->display_as('barang_id', 'Barang');
            $crud->display_as('qty', 'Jumlah');

            $crud->required_fields('barang_id', 'qty');
            $crud->field_type('tgl', 'hidden');
            $crud->field_type('gudang_id', 'hidden');
            $crud->field_type('user_id', 'hidden');

            $crud->unset_edit();

            $crud->callback_before_insert(array($this, 'stok_opname_callback'));

            // $crud->set_relation('user_id', 'user', 'nama_lengkap');
            $crud->set_relation('barang_id', 'barang', '{nama} - {kategori}');
            $crud->callback_column('user_id',array($this,'stok_opname_callback_user'));
            // $crud->order_by('kategori ASC');

            // $crud->add_fields(array('barang_id','qty','keterangan'));

            $output = $crud->render();

            $this->_page_output($output);
        } catch (Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function stok_opname_callback_user($value, $row){
      $user = $this->db->get_where('user',array('id' => $row->user_id))->row();

      return $user->nama_lengkap;
    }

    public function stok_opname_callback($post_array, $primary_key = null)
    {
        $post_array['gudang_id'] = $this->gudang_id;
        $post_array['user_id'] = $this->user_id;
        $post_array['tgl'] = date('Y-m-d H:i:s');

        return $post_array;
    }


    public function barang()
    {
        try {
            $crud = new Grocery_CRUD();

            $crud->set_table('barang');
            $crud->set_subject('Data Barang');
            $crud->order_by('nama', 'ASC');

            $crud->display_as('nama', 'Nama Barang');
            $crud->display_as('satuan_id', 'Satuan');

            $crud->required_fields('nama', 'kategori', 'satuan_id');
            $crud->field_type('updated_at', 'hidden');

            $crud->set_relation('satuan_id', 'satuan', 'nama');

            $output = $crud->render();

            $this->_page_output($output);
        } catch (Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    private function _insert_tracking($permintaan_id, $status,$current_user = null)
    {
        $user_id = $current_user;

        if($user_id == null){
          $this->db->select('current_user');
          $user_id = $this->db->get_where('permintaan', array('id' => $permintaan_id))->row()->current_user;
        }

        $input = array(
                  'tgl' => date('Y-m-d H:i:s'),
                  'user_id' => $user_id,
                  'permintaan_id' => $permintaan_id,
                  'status' => $status,
               );

        $this->db->insert('tracking_permintaan', $input);
    }

    private function _update_current_user($permintaan_id, $status, $user_id)
    {
        $this->db->where('id', $permintaan_id);
        $this->db->update(
                        'permintaan', array('current_user' => $user_id,
                                           'status' => $status, )
                       );
    }

    public function get_select_gudang()
    {
        $jenis = $_GET['jenis'];

        $option_gudang = '';

        $current_gudang = array($this->gudang_id);
        $rs = null;

        switch ($jenis) {

          case 'peminjaman':
            $this->db->where_not_in('id', $current_gudang);
            $rs = $this->db->get('gudang');
            break;

          case 'pembelian':
            $this->db->where_in('id', $current_gudang);
            $rs = $this->db->get('gudang');
            break;

          case 'pemakaian':
            $this->db->where_in('id', $current_gudang);
            $rs = $this->db->get('gudang');
            break;

          case 'pengembalian':
            $this->db->where_not_in('id', $current_gudang);
            $rs = $this->db->get('gudang');
            break;

          default:

            break;
        }

        $option_gudang .= "<option value=''>Pilih gudang tujuan</option>";

        $value = set_value('tujuan_gudang');
        foreach ($rs->result_array() as $r) {
          if($value === $r['id']){
            $option_gudang .= "<option selected value='".$r['id']."'>".$r['nama'].'</option>';
          }else{
            $option_gudang .= "<option value='".$r['id']."'>".$r['nama'].'</option>';
          }

        }

        echo $option_gudang;
    }

    /**
     * permintaan_action dilakukan pada gudang yang menjadi tujuan permintaan.
     **/
    public function permintaan_action($permintaan_id = null, $status = null, $next_user)
    {
        $this->db->select('jenis');
        $jenis = $this->db->get_where('permintaan', array('id' => $permintaan_id))->row()->jenis;

        $this->_insert_tracking($permintaan_id, $status);

        if ($jenis === 'peminjaman') {
            switch ($status) {

              case '100':
                //menunggu tanggapan user selanjutnya

                $this->_update_current_user($permintaan_id, '500', $next_user);
                redirect('manage/permintaan/masuk', 'reload');

                break;

              //ditolak
              case '110':

                $this->_update_current_user($permintaan_id, '110', $next_user);
                redirect('manage/permintaan/masuk', 'reload');

                break;

              //barang permintaan diproses
              case '200':
                //Barang permintaan menunggu dikirimkan
                $this->_update_current_user($permintaan_id, '205', $next_user);
                redirect('manage/permintaan/masuk', 'reload');

                break;

              //Barang permintaan telah dikirimkan
              case '210':

                $this->_update_current_user($permintaan_id, '210', $next_user);
                redirect('manage/permintaan/masuk', 'reload');

                break;

              //Barang permintaan telah diterima pihak peminta
              case '220':

                  $this->_update_current_user($permintaan_id, '220', $next_user);
                  redirect('manage/permintaan/keluar', 'reload');

                  break;
            }


        } elseif ($jenis === 'pembelian') {
            /*
            -internal gudang
            1.produksi mengirimkan permintaan ke logistik
            2.logistik memberikan tanggapan
              * jika diterima
                2a.logistik mengirimkan permintaan persetujuan ke pimpinan
                2b.pimpinan memberikan tanggapan
                2c.jika diterima maka
                   2c.a logistik mengirimkan pemesanan bahan dan alat ke suplier
                   2c.b logistik menerima barang masuk
              * jika ditolak selesai


            */

            switch ($status) {
              //ditolak
              case '110':

                $this->_update_current_user($permintaan_id, '110', $next_user);
                redirect('manage/permintaan/masuk', 'reload');

                break;
              case '100':

                if($this->user_level !== 'pimpinan'){
                  // $this->_update_current_user($permintaan_id, '110', $next_user);
                  // $this->_insert_tracking($permintaan_id, '100',$this->user_id);

                  sleep(2);

                  $this->_insert_tracking($permintaan_id, '700',$next_user);
                  $this->_update_current_user($permintaan_id, '500', $next_user);
                  redirect('manage/permintaan/masuk', 'reload');
                }else{
                  $this->_update_current_user($permintaan_id, '400', $next_user);
                  redirect('manage/permintaan/masuk', 'reload');
                }

                break;

              case '420':
                // $this->_insert_tracking($permintaan_id, '700',$next_user);
                $this->_update_current_user($permintaan_id, '205', $next_user);
                redirect('manage/permintaan/masuk', 'reload');
                break;

              //Barang permintaan telah diterima pihak peminta
              case '220':

                  $this->_update_current_user($permintaan_id, '220', $next_user);
                  redirect('manage/permintaan/masuk', 'reload');

                  break;
            }
        } elseif ($jenis === 'pemakaian') {
            switch ($status) {
              //ditolak
              case '110':

                $this->_update_current_user($permintaan_id, '110', $next_user);
                redirect('manage/permintaan/masuk', 'reload');

                break;

              case '100':
                //log
                $this->_insert_tracking($permintaan_id, '205',$next_user);
                sleep(2);
                $this->_update_current_user($permintaan_id, '205', $next_user);

                redirect('manage/permintaan/masuk', 'reload');
                break;
            }
        } elseif ($jenis === 'pengembalian') {
            # code...
        } else {
            redirect('manage', 'reload');
        }
    }
    //
    // public function get_tujuan_user($jenis)
    // {
    //     $select_option = '';
    //
    //     switch ($jenis) {
    //       case 'peminjaman':
    //         #produksi -> produksi (antar gudang)
    //         break;
    //
    //       case 'pembelian':
    //           # produksi -> logistik (internal gudang)
    //         break;
    //
    //       case 'pemakaian':
    //           # produksi -> logistik (internal gudang)
    //         break;
    //
    //       case 'pengembalian':
    //           # produksi ->produksi (antar gudang)
    //         break;
    //
    //       default:
    //         # code...
    //         break;
    //     }
    // }

    public function detail_permintaan()
    {
        $permintaan_id = $_GET['id'];
        $detail = $this->Argotuhu_model->get_permintaan_detail($permintaan_id);

        $row_table = '';
        if ($detail->num_rows() > 0) {
            foreach ($detail->result_array() as $row) {
                $row_table .= '<tr>
                                <td>'.ucwords($row['kategori']).'</td>
                                <td>'.$row['barang'].'</td>
                                <td>'.ucwords($row['jumlah']).'</td>
                                <td>'.$row['keterangan'].'</td>
                              </tr>';
            }
        } else {
            $row_table .= '<tr>
                              <td colspan="4" style="text-align: center; font-weight: bold">
                                 <div class="alert alert-danger" style="margin-bottom: 0px">Data barang tidak ada</div>
                              </td>
                           </tr>';
        }

        echo $row_table;
    }

    public function permintaan($kategori = 'masuk', $act = null, $param = null)
    {
        $this->load->library('SesItem');

        if ($act === 'add') {

            $array['page_name'] = 'f_permintaan';
            $array['nomor_baru'] = $this->Argotuhu_model->get_nomorbaru('permintaan');
            $this->_page_output($array);

        } elseif ($act === 'add-act') {

            if(count($this->sesitem->contents()) == 0){
              //detail ngga ada
              $array['msg'] = array(
                              'content'   => 'Detail permintaan masih kosong!',
                              'css_class' => 'alert alert-danger',
              );

              $array['page_name'] = 'f_permintaan';
              $array['nomor_baru'] = $this->Argotuhu_model->get_nomorbaru('permintaan');
              $this->_page_output($array);

            }else{

              $this->form_validation->set_rules('jenis', 'Jenis Permintaan', 'required');
              $this->form_validation->set_rules('tanggal','Tanggal','required');
              $this->form_validation->set_rules('tujuan_gudang','Gudang Tujuan','required');

              if ($this->form_validation->run() == true) {
                  $jenis = $this->input->post('jenis');
                  $tgl = mysqldateformat($this->input->post('tanggal'));
                  $tgl_batas = mysqldateformat($this->input->post('tgl_batas'));
                  $dari_gudang = $this->gudang_id;
                  $dari_user = $this->user_id;
                  $tujuan_gudang = $this->input->post('tujuan_gudang');

                  $tujuan_user = 0;
                  $current_user = 0;

                  switch ($jenis) {
                    case 'peminjaman':

                      //kirimkan ke bagian produksi
                      $tujuan_user = $this->Argotuhu_model->user_produksi($tujuan_gudang);
                      $current_user = $this->Argotuhu_model->user_produksi($tujuan_gudang);
                      break;

                    case 'pembelian':
                        # produksi -> logistik (internal gudang)
                        $tujuan_user = $this->Argotuhu_model->user_logistik($tujuan_gudang);
                        $current_user = $this->Argotuhu_model->user_logistik($tujuan_gudang);
                      break;

                    case 'pemakaian':
                        # produksi -> logistik (internal gudang)
                        $tujuan_user = $this->Argotuhu_model->user_logistik($tujuan_gudang);
                        $current_user = $this->Argotuhu_model->user_logistik($tujuan_gudang);
                      break;

                    case 'pengembalian':
                        # produksi ->produksi (antar gudang)
                      break;

                    default:
                      # code...
                      break;
                  }

                  //insert head
                  $head = array(
                            'jenis'         => $jenis,
                            'tgl'           => $tgl,
                            'tgl_batas'     => $tgl_batas,
                            'dari_gudang'   => $dari_gudang,
                            'dari_user'     => $dari_user,
                            'tujuan_gudang' => $tujuan_gudang,
                            'tujuan_user'   => $tujuan_user,
                            'current_user'  => $current_user
                  );

                  $permintaan_id = $this->Basecrud_m->insert('permintaan', $head);

                  //permintaan dibuat
                  $this->_insert_tracking($permintaan_id, '600',$dari_user);
                  //menunggu tanggapan
                  //$this->_insert_tracking($permintaan_id, '500',null);
                  //$permintaan_id, $status, $user_id
                  $this->_update_current_user($permintaan_id,'500',$current_user);

                  foreach ($this->sesitem->contents() as $item) {
                      $this->Basecrud_m->insert('permintaan_detail',
                        array(
                          'permintaan_id' => $permintaan_id,
                          'barang_id' => $item['barang_id'],
                          'qty' => $item['qty'],
                          'keterangan' => $item['keterangan'],
                        )
                    );
                  }

                  $this->sesitem->destroy();
                  redirect('manage/permintaan/keluar', 'reload');

              } else {
                  //validation error
                  $array['msg'] = array(
                                  'content' => validation_errors(),
                                  'css_class' => 'alert alert-warning',
                  );

                  $array['page_name'] = 'f_permintaan';
                  $array['nomor_baru'] = $this->Argotuhu_model->get_nomorbaru('permintaan');
                  $this->_page_output($array);
              }

            }


        } elseif ($act === 'add-detail') {
            if (!empty($_POST)) {
                $barang_id = $this->input->post('barang_id');
                $qty = $this->input->post('qty');
                $keterangan = $this->input->post('keterangan');

                $this->sesitem->insert(array(
                                       'id' => uniqid(),
                                       'barang_id' => $barang_id,
                                       'qty' => $qty,
                                       'keterangan' => $keterangan, )
                            );
            }

            $table_row = '';

            foreach ($this->sesitem->contents() as $item) {
                $barang = $this->db->get_where('barang', array('id' => $item['barang_id']))->row();
                $satuan = $this->db->get_where('satuan', array('id' => $barang->satuan_id))->row();

                $nama = $barang->nama;
                $kategori = ucwords($barang->kategori);
                $nama_satuan = $satuan->nama;

                $table_row .= "<tr id='row_".$item['rowid']."'>
                                <td>".$kategori.'</td>
                                <td>'.$nama.'</td>
                                <td>'.$item['qty'].' '.$nama_satuan.'</td>
                                <td>'.$item['keterangan']."</td>
                                <td>
                                  <div class=\"btn-group\">
                                     <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"del('".$item['rowid']."')\"><i class=\"fa fa-trash-o\"></i> Hapus</button>
                                  </div>
                                </td>
                              </tr>";
            }

            echo $table_row;
        } elseif ($act === 'del-detail') {
            $this->sesitem->remove(array('rowid' => $param));
            echo count($this->sesitem->contents());
        } else {

            $array['page_name'] = 'l_permintaan';
            if($this->user_level !== 'pimpinan'){

              $array['data'] = $this->Argotuhu_model->get_permintaan($kategori);

            }else{
              $array['data'] = $this->Argotuhu_model->get_permintaan($kategori,'2017-01-07');
            }


            $array['kategori'] = $kategori;

            $this->_page_output($array);
        }
    }

    public function users()
    {
        try {
            $crud = new Grocery_CRUD();

            $crud->set_table('user');
            $crud->set_subject('Daftar Akun User');

            $state = $crud->getState();
            if ($state === 'edit') {
                $crud->field_type('password', 'hidden');
            } else {
                $crud->field_type('password', 'password');
            }

            $crud->columns('username', 'nama_lengkap');
            $crud->required_fields('level', 'password', 'nama_lengkap', 'username');

            $crud->display_as(
                array('nama_lengkap' => 'Nama Lengkap',
                      'password' => 'Password',
                      'username' => 'Nama Login',
                     )
            );

            $crud->set_rules('telepon', 'Telepon', 'numeric');

            $crud->callback_before_insert(array($this, 'encrypt_password_callback'));
            //$crud->callback_before_update(array($this,'encrypt_password_callback'));

            $crud->add_action('Reset Password', '', 'manage/resetpassword', 'reset-password-icon');

            $crud->unset_read_fields('password');
            $crud->field_type('updated_at', 'hidden');

            $output = $crud->render();

            $this->_page_output($output);
        } catch (Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function resetpassword($id)
    {
        $this->db->where('id', $id);
        $this->db->update('user', array('password' => md5('user123')));

        echo "<script>alert('Password baru : user123');</script>";
        echo "<script>window.location = '".base_url()."manage/users'</script>";
    }

    public function satuan_barang()
    {
        try {
            $crud = new Grocery_CRUD();

            $crud->set_table('satuan');
            $crud->set_subject('Daftar Satuan Barang');

            $crud->required_fields('nama');
            $crud->order_by('nama', 'asc');

            $crud->columns('nama', 'keterangan');
            $crud->field_type('updated_at', 'hidden');

            $crud->display_as(
                array('updated_at' => 'Tgl. Update')
            );

            $output = $crud->render();

            $this->_page_output($output);
        } catch (Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function encrypt_password_callback($post_array, $primary_key = null)
    {
        $post_array['password'] = md5($post_array['password']);

        return $post_array;
    }

    public function profile()
    {
        $user_id = $this->session->userdata('user_id');

        if (!empty($_POST)) {
            $this->form_validation->set_rules('nama_lengkap', 'Nama lengkap', 'required');
            $this->form_validation->set_rules('telepon', 'telepon', 'numeric');

            if ($this->form_validation->run() == true) {
                if (!empty($_POST['pass_lama'])) {
                    $password = md5($this->input->post('pass_lama'));

                    $cek_user = $this->Basecrud_m->get_where('user',
                                      array('id' => $user_id, 'password' => $password));

                    if ($cek_user->num_rows() > 0) {
                        if (empty($_POST['pass_baru']) || empty($_POST['pass_ulangi'])) {
                            $data['msg'] = array(
                                            'content' => 'Password Baru / Ulangi tidak boleh kosong',
                                            'css_class' => 'alert alert-danger', );
                        } else {
                            $pass_baru = $this->input->post('pass_baru');
                            $pass_ulangi = $this->input->post('pass_ulangi');

                            if ($pass_baru !== $pass_ulangi) {
                                $data['msg'] = array(
                                              'content' => 'Password Baru & Ulangi Harus Sama!',
                                              'css_class' => 'alert alert-danger', );
                            } else {
                                $nama_lengkap = $this->input->post('nama_lengkap');
                                $telepon = $this->input->post('telepon');

                                $this->Basecrud_m->update('user',
                                                        $user_id,
                                                        array('password' => md5($pass_ulangi),
                                                              'nama_lengkap' => $nama_lengkap,
                                                              'telepon' => $telepon, ));
                                $data['msg'] = array(
                                              'content' => 'Profile berhasil diupdate',
                                              'css_class' => 'alert alert-success', );
                            }
                        }
                    } else {
                        $data['msg'] = array(
                                      'content' => 'Password Lama Salah',
                                      'css_class' => 'alert alert-danger', );
                    }
                } else {
                    $nama_lengkap = $this->input->post('nama_lengkap');
                    $telepon = $this->input->post('telepon');

                    $this->Basecrud_m->update('user',
                                            $user_id,
                                            array('nama_lengkap' => $nama_lengkap,
                                                  'telepon' => $telepon, ));
                    $data['msg'] = array(
                                  'content' => 'Profile berhasil diupdate',
                                  'css_class' => 'alert alert-success', );
                }
            } else {
                $data['msg'] = array(
                            'content' => validation_errors(),
                            'css_class' => 'alert alert-danger', );
            }

            $data['p'] = $this->Basecrud_m->get_where('user', array('id' => $user_id))->row();

            $data['page_name'] = 'profile';
            $this->_page_output($data);
        } else {
            $data['p'] = $this->Basecrud_m->get_where('user', array('id' => $user_id))->row();

            $data['page_name'] = 'profile';
            $this->_page_output($data);
        }
    }

    public function set_permintaan_filter($kategor){

      //01/01/2017
      $arrtgl = explode('/',$_POST['tgl_permintaan']);
      $tgl_permintaan = $arrtgl[2] . '-' . $arrtgl[0] . '-' . $arrtgl[1];

      $this->session->set_userdata('tgl_permintaan',$tgl_permintaan);
      // echo $tgl_permintaan;

      // if($this->user_level === 'pimpinan'){
      //   redirect('manage/permintaan');
      // }else{
      //   redirect('manage/permintaan/' . $kategori);
      // }
    }

    public function clear_permintaan_filter($kategori){

      $this->session->unset_userdata('tgl_permintaan');

      if($this->user_level === 'pimpinan'){
        redirect('manage/permintaan');
      }else{
        redirect('manage/permintaan/' . $kategori);
      }

    }
    public function stok(){

      $recordset = $this->db->query("SELECT b.id,
                                            b.nama,
                                            b.kategori,
                                            c.nama as satuan,
                                            a.qty,
                                            a.qty_piutang,
                                            a.qty_hutang,
                                            a.updated_at
                                     FROM stok a
                                     LEFT JOIN barang b ON a.barang_id = b.id
                                     LEFT JOIN satuan c ON b.satuan_id = c.id
                                     WHERE a.gudang_id = $this->gudang_id");
      $data['stok'] = $recordset;
      $data['page_name'] = 'stok';
      $this->_page_output($data);

    }


    public function jadwal_pengembalian(){

      $gudang_id = $this->gudang_id;

      $recordset = $this->db->query(
                       "SELECT a.id,
                               b.id as id_gudang_peminta,
                               b.nama AS dari,
                               c.nama AS ke,
                               c.id AS id_gudang_pemberi,
                               a.tgl_batas,
                               a.tgl,
                               ABS(IFNULL(DATEDIFF(a.tgl_batas, NOW()),0)) AS selisih
                        FROM permintaan a
                        LEFT JOIN gudang b ON a.dari_gudang = b.id
                        LEFT JOIN gudang c ON a.tujuan_gudang = c.id
                        WHERE a.jenis = 'peminjaman'
                              AND a.status = 205
                              AND (a.dari_gudang =  $gudang_id OR a.tujuan_gudang = $gudang_id)
                        ORDER BY ABS(IFNULL(DATEDIFF(a.tgl_batas, NOW()),0)) DESC
                        ");

      $data['jadwal_pengembalian'] = $recordset;
      $data['page_name'] = 'jadwal_pengembalian';
      $this->_page_output($data);
    }

    // function laporan_permintaan(){
    //
    //   $tanggal_permintaan = $_POST['tanggal_permintaan'];
    //
    //   $array['page_name'] = 'l_laporan_permintaan';
    //
    //   if($this->user_level !== 'pimpinan'){
    //     $array['data'] = $this->Argotuhu_model->get_permintaan($kategori);
    //   }else{
    //     $array['data'] = $this->Argotuhu_model->get_permintaan($kategori);
    //   }
    //
    //
    //   $array['kategori'] = $kategori;
    //
    //   $this->_page_output($array);
    //
    //
    //
    // }
}
