  <?php

       function inbox()
    {
        try {
            $crud = new Grocery_CRUD();

            $crud->set_table('inbox');
            $crud->set_subject('SMS Masuk');

            $crud->columns('ReceivingDateTime', 'SenderNumber', 'TextDecoded');

            $crud->unset_add();
            // $crud->unset_delete();
            $crud->unset_print();
            $crud->unset_export();
            $crud->unset_edit();

            $crud->display_as(
              array('ReceivingDateTime' => 'Tanggal',
                    'SenderNumber' => 'Nomor Pengirim',
                    'TextDecoded' => 'Isi Pesan',
                  )
          );

            $output = $crud->render();

            $this->_page_output($output);
        } catch (Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

        // $this->load->library('SesItem');
    // $this->sesitem->destroy();
    //
    // $this->sesitem->insert(array(    'id'                => uniqid(),'nama' => 'test'));
    // $this->sesitem->insert(array(    'id'                => uniqid(),'nama' => 'test'));
    //
    // foreach ($this->sesitem->contents() as $item) {
    //   echo strtoupper($item['id']) . "<br />";
    // }
    //
        // public function remove_aduan_penanganan($penanganan_id)
    // {
    //     $this->db->where('id', $penanganan_id);
    //     $this->db->delete('aduan_penanganan');
    //
    //     redirect('manage/daftar-aduan');
    // }

    // public function insert_aduan_penanganan()
    // {
    //     $aduan_id = $_POST['aduan_id'];
    //     $pegawai_id = $_POST['pegawai_id'];
    //
    //     $this->db->insert('aduan_penanganan',
    //             array('aduan_id' => $aduan_id,
    //                   'pegawai_id' => $pegawai_id, )
    //   );
    // }

    // public function _callback_status($value, $row)
    // {
    //     $aduan_id = $row->id;
    //
    //     $this->db->select('a.id,b.nomor_pegawai,b.nama_lengkap');
    //     $this->db->join('pegawai b', 'a.pegawai_id = b.id', 'left');
    //     $this->db->where('a.aduan_id', $aduan_id);
    //     $penanganan = $this->db->get('aduan_penanganan a');
    //
    //     $html_pegawai =
    //     '<select class="select2" style="width:100%" aduan_id="'.$aduan_id.'">
    //         <option>[ Pilih Pegawai ]</option>';
    //     $pegawai = $this->db->get('pegawai');
    //     foreach ($pegawai->result_array() as $peg) {
    //         $html_pegawai .= '<option value="'.$peg['id'].'">'.$peg['nama_lengkap'].'</option>';
    //     }
    //     $html_pegawai .= '</select>';
    //
    //     $html_penanganan = '';
    //     foreach ($penanganan->result_array() as $p) {
    //         $delete = ($row->status_aduan === '001') ? "<td><a href='".site_url('manage/remove_aduan_penanganan/'.$p['id'])."' onclick=\"return confirm('Are you sure you want to delete this item?');\">   <i class=\"fa fa-remove\"></i></a></td>" : '';
    //         $html_penanganan .=
    //           '<tr>
    //             <td>'.$p['nomor_pegawai'].'</td>
    //             <td>'.$p['nama_lengkap'].'</td>
    //             '.$delete.'
    //
    //           </tr>';
    //     }
    //
    //     if ($row->status_aduan === '001') {
    //         return '<span class="status-001"><i class="fa fa-bell"></i>
    //                 001 - Aduan Baru
    //                   <table>'.$html_penanganan.'
    //                   <tr>
    //                     <td  colspan="2">'.$html_pegawai.' </td>
    //                   </tr>
    //                   </table>
    //                 </span>';
    //     } elseif ($row->status_aduan === '002') {
    //         return '<span class="status-002"><i class="fa fa-hourglass-start"></i>
    //                   002 - Dalam Proses
    //                   <br />Mulai : '.$row->mulai_penanganan.
    //                 ' <table>'
    //                   .$html_penanganan.'
    //                   <!--<tr>
    //                     <td  colspan="2">'.$html_pegawai.' </td>
    //                   </tr>-->
    //
    //                    </table>
    //                 </span>';
    //     } elseif ($row->status_aduan === '003') {
    //         return '<span class="status-003"><i class="fa fa-check"></i>
    //                 003 - Penanganan Selesai<br />
    //                   <table>
    //                     <tr>
    //                       <td>Mulai</td>
    //                       <td> : '.$row->mulai_penanganan.'</td>
    //                     </tr>
    //                     <tr>
    //                       <td>Selesai</td>
    //                       <td> : '.$row->selesai_penanganan.'</td>
    //                     </tr>
    //                     '.$html_penanganan.'
    //                     <!--<tr>
    //                       <td  colspan="2">'.$html_pegawai.' </td>
    //                     </tr>-->
    //                   </table>
    //                 </span>';
    //     } elseif ($row->status_aduan === '004') {
    //         return '<span class="status-004"><i class="fa fa-warning"></i>
    //                 004 - Lokasi Tidak Ditemukan<br />
    //                   <table>
    //                     <!--<tr>
    //                       <td>Mulai</td>
    //                       <td> : '.$row->mulai_penanganan.'</td>
    //                     </tr>
    //                     <tr>
    //                       <td>Selesai</td>
    //                       <td> : '.$row->selesai_penanganan.'</td>
    //                     </tr>
    //                     -->
    //                     '.$html_penanganan.'
    //                     <!--<tr colspan="2">
    //                       <td  colspan="2">'.$html_pegawai.' </td>
    //                     </tr>-->
    //                   </table>
    //                 </span>';
    //     }
    // }

    // public function test_kirim_sms_panjang(){
    //     $pesan = "Screen readers will have trouble with your forms if you don't include a label for every input. For these inline forms, you can hide the labels using the .sr-only class. There are further alternative methods of providing a label for assistive technologies, such as the aria-label, aria-labelledby or title attribute. If none of these is present, screen readers may resort to using the placeholder attribute, if present, but note that use of placeholder as a replacement for other labelling methods is not advised.";
    //     $noHp = "081217088807";
    //     kirim_sms($pesan,$noHp);
    // }


    // public function kirim_sms()
    // {
    //     $data['page_title'] = 'Form Kirim SMS';
    //     $data['page_name'] = 'kirim_sms';
    //
    //     if (!empty($_POST)) {
    //         $pesan = $_POST['pesan'];
    //         $tujuan = $_POST['tujuan'];
    //
    //         foreach ($tujuan as $noTujuan) {
    //             kirim_sms($pesan, $noTujuan);
    //         }
    //
    //         $data['msg'] = array(
    //                     'content' => 'Pesan berhasil dikirimkan',
    //                     'css_class' => 'alert alert-success', );
    //     }
    //
    //     $data['pegawai'] = $this->db->get('pegawai');
    //     $this->_page_output($data);
    // }
    //
     function barang_masuk($cmd = null)
    {
        if ($cmd === 'add') {
            $data['page_name'] = 'sirkulasi/f_masuk';
        } else {
            $data['page_name'] = 'sirkulasi/l_masuk';
        }

        $data['recordset'] = $this->db->get('barang_masuk');
        $this->_page_output($data);
    }

     function _callback_tgl_aduan($value, $row)
    {
        return $row->tgl_aduan.' '.$row->waktu_aduan;
    }

     function kode_status()
    {
        try {
            $crud = new Grocery_CRUD();
            $crud->set_table('aduan_status');
            $crud->set_subject('Status penanganan');

            $crud->field_type('kode', 'readonly');
            $crud->required_fields('arti');

            $crud->unset_add();
            $crud->unset_delete();
            $crud->unset_print();
            $crud->unset_export();
            $crud->unset_edit();

            $crud->columns('kode', 'arti', 'keterangan');

            $output = $crud->render();

            $this->_page_output($output);
        } catch (Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

     function settings()
    {
        try {
            $crud = new Grocery_CRUD();
            $crud->set_table('settings');
            $crud->set_subject('Settings');

            $crud->field_type('kode', 'readonly');
            $crud->required_fields('nilai');

            $crud->unset_add();
            $crud->unset_delete();
            $crud->unset_print();
            $crud->unset_export();

            $crud->columns('kode', 'nilai', 'keterangan');

            $output = $crud->render();

            $this->_page_output($output);
        } catch (Exception $e) {
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

     function getnewmsg()
    {
        header('content-type: application/json');

        $this->db->where('status_aduan', '001');
        $aduan_baru = $this->db->get('aduan');

        $this->db->where('status_aduan', '002');
        $aduan_proses = $this->db->get('aduan');

        $this->db->where('status_aduan', '003');
        $aduan_selesai = $this->db->get('aduan');

        $this->db->where('status_aduan', '004');
        $aduan_error = $this->db->get('aduan');

        echo json_encode(
              array(
                'msgbaru' => $aduan_baru->num_rows(),
                'msgproses' => $aduan_proses->num_rows(),
                'msgselesai' => $aduan_selesai->num_rows(),
                'msgtidakditemukan' => $aduan_error->num_rows(),
            ));
    }

    function sirkulasi_barang($jenis = 'sirkulasi-masuk', $cmd = null, $param = null)
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
            $data['nomor_baru'] = $this->Argotuhu_model->get_nomorbaru();
            $data['page_name'] = 'f_sirkulasi_barang';

            $this->_page_output($data);
        } elseif ($cmd === 'add_act') {
            $this->form_validation->set_rules('nomor', 'Nomor Sirkulasi', 'required|is_unique[sirkulasi_barang.nomor]');
            $this->form_validation->set_rules('nomor_permintaan', 'Nomor Permintaan', 'required');
            if ($this->form_validation->run() == true) {
                $nomor = $this->input->post('nomor');
                $nomor_permintaan = $this->input->post('nomor_permintaan');
                $gudang_id = $this->session->userdata('user_gudang');
                $persetujuan_user = $this->session->userdata('user_id');
                $catatan = $this->session->userdata('catatan');

                $head = array('gudang_id' => $gudang_id,
                          'jenis' => $jenis,
                          'nomor' => $nomor,
                          'nomor_permintaan' => $nomor_permintaan,
                          'persetujuan_user' => $persetujuan_user,
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
            } else {
                $data['msg'] = array(
                              'content' => validation_errors(),
                              'css_class' => 'alert alert-info',
              );
            }
        } else {
            $data['jenis'] = $jenis;
            $data['page_name'] = 'l_sirkulasi_barang';
            $data['recordset'] = $this->Argotuhu_model->get_sirkulasi($jenis);

            $this->_page_output($data);
        }
    }

 function get_sirkulasi($jenis)
{
    $gudang_id = $this->session->userdata('user_gudang');

    $rs = $this->db->query("SELECT a.id,a.tgl,a.nomor AS nomor_sirkulasi,
                                   a.nomor_permintaan,
                                   b.nama_lengkap AS pemeriksa,
                                   a.catatan
                            FROM sirkulasi_barang a
                            LEFT JOIN user b ON a.persetujuan_user = b.id
                            WHERE a.gudang_id = $gudang_id AND jenis = '$jenis'
                            ORDER BY a.tgl DESC");

    return $rs;
}

 function get_nomorbaru()
{
    $this->db->select('IFNULL(max(id)+1,1) AS nomor_baru');
    return $this->db->get('sirkulasi_barang')->row()->nomor_baru;
}

 function get_sirkulasi_detail($sirkulasi_barang_id)
{
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


// 
// $('#warna-tersedia').change(function(){
//     var warna = $(this).val();
//
//     $('#div-qty').hide();
//
//     if (warna !== 'pilih-warna') {
//         get_ukuran(warna);
//         $('#div-ukuran-tersedia').show();
//     }else{
//         $('#div-ukuran-tersedia').hide();
//
//     }
// });
//
// function get_ukuran(var_warna){
//
//     $.get( "<?php echo base_url() . 'web/get_ukuran'?>",
//         {id_produk : <?php echo $details->id?>,
//          warna : var_warna }
//     ).done(function(data){
//         $('#ukuran-tersedia').html(data);
//     });
// }
