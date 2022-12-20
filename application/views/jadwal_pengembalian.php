<?php $gudang_id = $this->session->userdata('user_gudang');?>
<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
              <th>Kode Peminjaman</th>
              <th>Dari</th>
              <th>Ke</th>
              <th>Tanggal</th>
              <th>Tgl.Pengembalian</th>
              <th>Opsi</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>Kode Peminjaman</th>
              <th>Dari</th>
              <th>Ke</th>
              <th>Tanggal</th>
              <th>Tgl.Pengembalian</th>
              <th>Opsi</th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($jadwal_pengembalian->result_array() as $row) { ?>
              <?php

                //cek apakah permintaan itu ada alat,
                $jml_alat = 0;
                $permintaan_id = $row['id'];

                $cek_alat = $this->db->query(
                                "SELECT b.kategori
                                  FROM permintaan_detail a
                                  LEFT JOIN barang b ON a.barang_id = b.id
                                  WHERE permintaan_id = $permintaan_id
                ");

                foreach ($cek_alat->result_array() as $c) {
                  if($c['kategori'] === 'alat'){
                    $jml_alat += 1;
                  }
                }

                if($jml_alat == 0) continue;

              ?>
              <tr>
                  <td><?php echo str_pad(($row['id']),6,'0',STR_PAD_LEFT);?></td>
                  <td><?php echo $row['dari']?></td>
                  <td><?php echo $row['ke']?></td>
                  <td><?php echo $row['tgl'];?></td>
                  <td><?php echo $row['tgl_batas'] . ' ( ' . $row['selisih'] . ' hari lagi)' ;?></td>
                  <td>
                    <?php

                      $show_pengembalian_kurang = true;

                      if($gudang_id == $row['id_gudang_peminta']){ ?>
                      <?php
                       //cek apakah barang sudah dikembalikan dan tanggal brapa
                        $c = $this->db->get_where(
                              'sirkulasi_barang',
                              array('nomor_permintaan' => $row['id'],
                                    'pengembalian' => 'Y',
                                    'gudang_id' => $gudang_id,
                                    'jenis' => 'keluar' )
                        );

                        if($c->num_rows() > 0){ ?>
                          <div class="alert alert-info"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> [<?php echo date('d-m-Y',strtotime($c->row()->tgl))?>] Barang telah dikembalikan</div>

                        <?php
                          $d = $this->db->get_where(
                                'sirkulasi_barang',
                                array('nomor_permintaan' => $row['id'],
                                      'pengembalian' => 'Y',
                                      'jenis' => 'masuk' )
                          );

                          if($d->num_rows() > 0){ ?>
                          <div class="alert alert-info"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> [<?php echo date('d-m-Y',strtotime($d->row()->tgl))?>] Barang telah diterima</div>
                          <?php } ?>


                        <?php }else
                        {
                          $show_pengembalian_kurang = false; ?>
                          <a href="<?php echo site_url('manage/sirkulasi_barang/keluar/add/' . $row['id'] . '/Y')?>">Pengembalian</a>
                        <?php }  ?>


                        <?php if($show_pengembalian_kurang){ ?>
                          <?php
                            //cek apakah alat sudah dikembalikan semuanya
                            $total_pinjam = 0;
                            $total_kembali = 0;

                            $d = $this->db->get_where(
                                  'sirkulasi_barang',
                                  array('nomor_permintaan' => $row['id'],
                                        'pengembalian' => 'N',
                                        'jenis' => 'keluar',
                                        'gudang_id' => $row['id_gudang_pemberi'])
                            );

                            $b =  $this->db->get_where(
                                  'sirkulasi_barang',
                                  array('nomor_permintaan' => $row['id'],
                                        'pengembalian' => 'Y',
                                        'jenis' => 'masuk',
                                        'gudang_id' => $row['id_gudang_pemberi'])
                            );



                            $det_d = $this->db->get_where('sirkulasi_barang_detail',array('sirkulasi_barang_id' => $d->row()->id));

                            ?>
                            <table>
                              <tr>
                                <td>Barang</td>
                                <td>Jml Pinjam</td>
                                <td>Jml Kembali</td>
                              </tr>
                            <?php
                              foreach ($det_d->result_array() as $det_d_row) {
                                $brg = $this->db->get_where('barang',array('id' => $det_d_row['barang_id']));
                                $kategori = $brg->row()->kategori;
                                if($kategori !== 'alat') continue;
                            ?>
                              <tr>
                                <td>
                                <?php
                                  $brg = $this->db->get_where('barang',array('id' => $det_d_row['barang_id']));
                                  $nama_barang = $brg->row()->nama;
                                  echo $nama_barang ;
                                ?>
                                </td>
                                <td>
                                  <?php
                                    $sirkulasi_barang_id = $d->row()->id;
                                    $barang_id = $det_d_row['barang_id'];

                                    $total_pinjam = $this->db->query("SELECT SUM(qty) AS jml
                                                      FROM sirkulasi_barang_detail
                                                      WHERE sirkulasi_barang_id = $sirkulasi_barang_id
                                                            AND barang_id = $barang_id")->row()->jml;

                                    echo $total_pinjam;

                                  ?>
                                </td>
                                <td>

                                  <?php
                                    $barang_id = $det_d_row['barang_id'];

                                    foreach ($b->result_array() as $det_b) {
                                      $sirkulasi_barang_id = $det_b['id'];

                                      $jml = $this->db->query("SELECT SUM(qty) AS jml
                                                               FROM sirkulasi_barang_detail
                                                               WHERE sirkulasi_barang_id = $sirkulasi_barang_id
                                                                      AND barang_id = $barang_id")->row()->jml;

                                      $total_kembali += $jml;
                                    }

                                    echo $total_kembali;
                                  ?>
                                </td>
                              </tr>
                            <?php } ?>
                          <?php if($total_pinjam > $total_kembali){ ?>
                            <tr>
                              <td colspan="3">
                                <a class="btn btn-danger pull-right" href="<?php echo site_url('manage/sirkulasi_barang/keluar/add/' . $row['id'] . '/Y')?>">Pengembalian</a>
                              </td>
                            </tr>
                          <?php } ?>
                          </table>
                        <?php } ?>

                    <?php }else{ ?>
                      <?php
                        $c = $this->db->get_where(
                              'sirkulasi_barang',
                              array('nomor_permintaan' => $row['id'],
                                    'pengembalian' => 'Y',
                                    'jenis' => 'keluar' )
                        );

                        if($c->num_rows() > 0){ ?>
                          <div class="alert alert-info"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> [<?php echo date('d-m-Y',strtotime($c->row()->tgl))?>] Barang telah dikirimkan</div>
                          <!-- <button type="button" class="btn btn-success">Success</button> -->

                          <?php
                          $d = $this->db->get_where(
                                'sirkulasi_barang',
                                array('nomor_permintaan' => $row['id'],
                                      'pengembalian' => 'Y',
                                      'jenis' => 'masuk' )
                          );

                          if($d->num_rows() > 0){ ?>
                          <div class="alert alert-info"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> [<?php echo date('d-m-Y',strtotime($d->row()->tgl))?>] Barang telah diterima</div>
                          <?php }else{
                            $show_pengembalian_kurang = false;?>
                            <a href="<?php echo site_url('manage/sirkulasi_barang/masuk/add/' . $row['id'] . '/Y')?>" class="btn btn-success"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span> Barang masuk</a>
                          <?php } ?>

                        <?php }  ?>

                        <?php if($show_pengembalian_kurang){ ?>
                          <?php
                            //cek apakah alat sudah dikembalikan semuanya
                            $total_pinjam = 0;
                            $total_kembali = 0;

                            $d = $this->db->get_where(
                                  'sirkulasi_barang',
                                  array('nomor_permintaan' => $row['id'],
                                        'pengembalian' => 'N',
                                        'jenis' => 'keluar',
                                        'gudang_id' => $row['id_gudang_pemberi'])
                            );

                            $b =  $this->db->get_where(
                                  'sirkulasi_barang',
                                  array('nomor_permintaan' => $row['id'],
                                        'pengembalian' => 'Y',
                                        'jenis' => 'masuk',
                                        'gudang_id' => $row['id_gudang_pemberi'])
                            );



                            $det_d = $this->db->get_where('sirkulasi_barang_detail',array('sirkulasi_barang_id' => $d->row()->id));

                            ?>
                            <table>
                              <tr>
                                <td>
                                  Barang
                                </td>
                                <td>
                                  Jml Pinjam
                                </td>
                                <td>
                                  Jml Kembali
                                </td>
                              </tr>

                            <?php foreach ($det_d->result_array() as $det_d_row) {
                              $brg = $this->db->get_where('barang',array('id' => $det_d_row['barang_id']));
                              $kategori = $brg->row()->kategori;
                              if($kategori !== 'alat') continue;
                              ?>
                              <tr>
                                <td>
                                <?php
                                  $brg = $this->db->get_where('barang',array('id' => $det_d_row['barang_id']));
                                  $nama_barang = $brg->row()->nama;
                                  echo $nama_barang;
                                ?>
                                </td>
                                <td>
                                  <?php
                                    $sirkulasi_barang_id = $d->row()->id;
                                    $barang_id = $det_d_row['barang_id'];

                                    $total_pinjam = $this->db->query("SELECT SUM(qty) AS jml
                                                      FROM sirkulasi_barang_detail
                                                      WHERE sirkulasi_barang_id = $sirkulasi_barang_id
                                                            AND barang_id = $barang_id")->row()->jml;

                                    echo $total_pinjam;

                                  ?>
                                </td>
                                <td>

                                  <?php

                                    $barang_id = $det_d_row['barang_id'];

                                    foreach ($b->result_array() as $det_b) {
                                      $sirkulasi_barang_id = $det_b['id'];

                                      $jml = $this->db->query("SELECT SUM(qty) AS jml
                                                               FROM sirkulasi_barang_detail
                                                               WHERE sirkulasi_barang_id = $sirkulasi_barang_id
                                                                      AND barang_id = $barang_id")->row()->jml;

                                      $total_kembali += $jml;


                                    }

                                    echo $total_kembali;

                                  ?>



                                </td>
                              </tr>

                            <?php }


                          ?>
                          <?php if($total_pinjam > $total_kembali){ ?>
                            <tr>
                              <td colspan="3">
                                <a class="btn btn-danger pull-right" href="<?php echo site_url('manage/sirkulasi_barang/masuk/add/' . $row['id'] . '/Y')?>">Terima pengembalian</a>
                              </td>
                            </tr>

                          <?php } ?>
                          </table>



                        <?php } ?>

                    <?php } ?>
                  </td>
              </tr>
            <?php } ?>
        </tbody>
    </table>

<script>
$('#example').DataTable( {
      // dom: 'Bfrtip',
      ordering: false

  } );

</script>
