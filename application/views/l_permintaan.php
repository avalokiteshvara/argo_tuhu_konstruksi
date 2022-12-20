<!-- $this->db->select("LPAD(a.id,6,'0') AS nomor,
                   DATE_FORMAT(a.tgl,'%e-%m-%Y') AS tanggal,
                   CONCAT(b.nama,\n,c.nama_lengkap) AS dari,
                   CONCAT(d.nama,\n,e.nama_lengkap) AS tujuan,
                   CONCAT(a.status,\n,f.keterangan) AS status"); -->
<?php
  $user_level = $this->session->userdata('user_level');
  $user_id = $this->session->userdata('user_id');

  $gudang_id = 0;
  $jabatan = "";
  $current_user_produksi = 0;
  $current_user_logistik = 0;
  $current_user_pimpinan = 0;

  if($user_level !== 'pimpinan'){
    $gudang_id = $this->session->userdata('user_gudang');
    $jabatan = $this->session->userdata('user_jabatan'); //produksi atau logistik

    $current_user_produksi = $this->Argotuhu_model->user_produksi($gudang_id);
    $current_user_logistik = $this->Argotuhu_model->user_logistik($gudang_id);
    $current_user_pimpinan = $this->Argotuhu_model->user_pimpinan();
  }else{
    $jabatan = 'pimpinan';
  }

?>

<div class="col-md-2" style="padding-left: 1px;border-bottom-width: 10px;padding-bottom: 10px;">
  <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?php echo @$this->session->userdata('tgl_permintaan')?>" style="width:100%">
</div>
<div class="col-md-5">
  <a class="btn btn btn-primary" href="<?php echo site_url('manage/clear_permintaan_filter/' . $kategori)?>">Clear Filter</a>
</div>



<table id="example" class="display cell-border stripe hover" cellspacing="0" width="100%">
    <thead>
        <tr>
          <th>Permintaan</th>
          <!-- <th>Tanggal</th> -->
          <th>Dari</th>
          <th>Tujuan</th>
          <th>Log Status</th>
          <th>Pilihan Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php

        foreach ($data->result_array() as $row) {

          //filter
          if($row['jenis'] === 'pembelian' && $kategori === 'masuk' && $jabatan === 'produksi') continue;
          if($row['jenis'] === 'pemakaian' && $kategori === 'masuk' && $jabatan === 'produksi') continue;

        ?>
          <tr>
              <td style="padding: 1px;">
                <table class="table table-condensed table-bordered">
                  <tr>
                    <td>Jenis</td>
                    <td><?php echo ucwords($row['jenis'])?></td>
                  </tr>
                  <tr>
                    <td>Tanggal</td>
                    <td><?php echo $row['tanggal'];?></td>
                  </tr>
                  <tr>
                    <td>Nomor</td>
                    <td><a href="#dataitem"  onclick="loaddetail('<?php echo $row['id']?>')" data-toggle="modal"><?php echo $row['nomor'];?> </a></td>
                  </tr>
                </table>
                <table class="table table-condensed table-bordered">
                  <tr>
                    <td>
                      Barang
                    </td>
                    <td>
                      Jumlah (Qty)
                    </td>
                  </tr>
                  <?php
                    $permintaan_id = $row['id'];
                    $det = $this->db->query("SELECT b.nama AS barang,
                                                    CONCAT(a.qty,' ',c.nama)  AS qty
                                      FROM permintaan_detail a
                                      LEFT JOIN barang b ON a.barang_id = b.id
                                      LEFT JOIN satuan c ON b.satuan_id = c.id
                                      WHERE a.permintaan_id = $permintaan_id");

                    foreach ($det->result_array() as $d) { ?>
                      <tr>
                        <td><?php echo $d['barang']?></td>
                        <td><?php echo $d['qty']?></td>
                      </tr>
                    <?php } ?>


                </table>
              </td>
              <!-- <td></td> -->
              <td><?php echo $row['dari'];?></td>
              <td><?php echo $row['tujuan'];?></td>
              <td class="<?php //echo $row['css_class']?>" style="padding: 1px;">
                <!-- <?php echo $row['status'];?> -->
                <table class="table table-condensed table-bordered">
                  <?php
                    $permintaan_id = $row['id'];
                    $log_status = $this->db->query("SELECT a.tgl,a.status,
                                                           b.keterangan,b.css_class,
                                                           c.nama_lengkap,
                                                           b.glyphs
                                                    FROM tracking_permintaan a
                                                    LEFT JOIN tracking_status b ON a.status = b.kode
                                                    LEFT JOIN user c ON a.user_id = c.id
                                                    WHERE permintaan_id = $permintaan_id
                                                    ORDER BY a.tgl ASC");
                    foreach ($log_status->result_array() as $log) {
                      $css_class = $log['css_class'];
                      echo "<tr>
                              <td>". $log['tgl'] ."</td>
                              <td class='$css_class'><span class='" . $log['glyphs']."' aria-hidden='true'></span> ". $log['keterangan'] . '<!--<br/>' . $log['nama_lengkap'] . "--></td>
                            </tr>";
                    }
                  ?>

                 <?php

                  $this->db->order_by('tgl ASC');
                  $sirkulasi = $this->db->get_where('sirkulasi_barang',array('nomor_permintaan' => $row['id'] ,'pengembalian' => 'N'));
                  foreach ($sirkulasi->result_array() as $sir) { ?>

                    <?php if($sir['jenis'] === 'masuk'){ ?>
                      <tr>
                        <td><?php echo $sir['tgl']?></td>
                        <td class="alert alert-info"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Barang telah diterima</td>
                      </tr>
                    <?php } else{ ?>
                      <tr>
                        <td><?php echo $sir['tgl']?></td>
                        <td class="alert alert-info"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> Barang telah dikirimkan</td>
                      </tr>
                    <?php } ?>

                  <?php } ?>

                </table>

              </td>
              <td style="text-align:center">

                <?php if($row['status_kode'] === '220'){ ?>
                  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                <?php }elseif($row['status_kode'] === '110'){ ?>
                  <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                <?php } ?>

                <?php

                $permintaan_id = $row['id'];
                $jenis = $row['jenis'];

                // echo $row['current_user'] . '-' . $user_id;

                if($row['current_user'] == $user_id)
                {
                  //gudang tujuan
                  if($jenis === 'peminjaman')
                  {
                    if($jabatan === 'produksi')
                    {
                      switch ($row['status_kode']) {
                        //Menunggu tanggapan
                        case '500':
                          $next_user = $current_user_logistik;

                          echo '<div class="btn-group">
                                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Aksi <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/110/$user_id") .'" style="color:#a94442"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Ditolak</a></li>
                                    <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/100/$next_user").'" style="color:#3c763d"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Disetujui</a></li>
                                  </ul>
                                </div>';

                          break;

                        //Permintaan ditolak
                        case '110':
                          // echo "Tidak Ada";
                          break;

                        case '210':
                          // echo "Tidak Ada";
                          break;

                        default:
                          # code...
                          break;
                      }
                    }elseif ($jabatan === 'logistik') {

                      switch ($row['status_kode']) {
                          case '500':
                            echo '<div class="btn-group">
                                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Aksi <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/200/$user_id") .'" style="color:#8a6d3b"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Diproses</a></li>
                                  </ul>
                                </div>';
                            break;

                          case '205':
                            // $next_user = $current_user_produksi;
                            //
                            // echo '<div class="btn-group">
                            //         <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            //           Aksi <span class="caret"></span>
                            //         </button>
                            //         <ul class="dropdown-menu">
                            //           <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/210/$next_user") .'" style="color:#8a6d3b"><span class="glyphicon glyphicon-play" aria-hidden="true"></span> Barang dikirimkan</a></li>
                            //         </ul>
                            //       </div>';

                          //  if($kategori === 'masuk'){
                             //barang keluar
                             $permintaan = $this->db->get_where('sirkulasi_barang',array('jenis' => 'keluar','nomor_permintaan' => $permintaan_id));

                             if($permintaan->num_rows() == 0){
                               echo '<div class="btn-group">
                                       <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         Aksi <span class="caret"></span>
                                       </button>
                                       <ul class="dropdown-menu">
                                         <li><a href="' . site_url("manage/sirkulasi-barang/keluar/add/" . $permintaan_id) .'" style="color:#3c763d"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> Barang keluar</a></li>
                                       </ul>
                                     </div>';

                            }
                          //    }
                           //
                          //  }else{
                          //    //barang masuk
                          //    $permintaan = $this->db->get_where('sirkulasi_barang',array('jenis' => 'masuk','nomor_permintaan' => $permintaan_id));
                          //    if($permintaan->num_rows() == 0){
                          //      echo '<div class="btn-group">
                          //              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          //                Aksi <span class="caret"></span>
                          //              </button>
                          //              <ul class="dropdown-menu">
                          //                <li><a href="' . site_url("manage/sirkulasi-barang/masuk/add/" . $permintaan_id) .'" style="color:#3c763d"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Barang masuk</a></li>
                          //              </ul>
                          //            </div>';
                           //
                           //
                          //    }

                          //  }

                            break;

                          case '210':
                              // echo "Tidak Ada";
                              break;

                          case '110':
                              // echo "Tidak Ada";
                              break;

                      }
                    }
                  }elseif($jenis === 'pembelian'){
                    /**
                    ** ************PEMBELIAN ***************
                    */

                    if($jabatan === 'produksi')
                    {

                    }elseif ($jabatan === 'logistik') {
                      switch ($row['status_kode']) {
                          case '500':
                            $next_user = $current_user_pimpinan;

                            echo '<div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Aksi <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/110/$user_id") .'" style="color:#a94442"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Ditolak</a></li>
                                      <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/100/$next_user").'" style="color:#3c763d"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Disetujui</a></li>
                                    </ul>
                                  </div>';

                            break;

                          case '400':

                            echo '<div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Aksi <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/420/$user_id") .'" style="color:#8a6d3b"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Pembelian dilakukan</a></li>
                                    </ul>
                                  </div>';
                            break;

                          case '205':
                            // echo '<div class="btn-group">
                            //         <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            //           Aksi <span class="caret"></span>
                            //         </button>
                            //         <ul class="dropdown-menu">
                            //           <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/220/$user_id") .'" style="color:#8a6d3b"><span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span> Barang telah diterima</a></li>
                            //         </ul>
                            //       </div>';
                            break;



                      }
                    }elseif($jabatan === 'pimpinan'){
                      switch ($row['status_kode']) {
                          case '500':
                           //next user adalah tujuan_user
                            $next_user = $row['tujuan_user'];

                            echo '<div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Aksi <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/110/$user_id") .'" style="color:#a94442"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Ditolak</a></li>
                                      <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/100/$next_user").'" style="color:#3c763d"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Disetujui</a></li>
                                    </ul>
                                  </div>';

                            break;

                      }
                    }

                  }elseif ($jenis === 'pemakaian') {
                    /**
                    PEMAKAIAN *************************************************
                    **/
                    if($jabatan === 'produksi'){
                      //jika kamu bagian produksi && permintaan  = masuk && jenis = pemakaian then skip

                    }elseif ($jabatan === 'logistik') {
                      switch ($row['status_kode']) {
                          case '500':
                            $next_user = $current_user_logistik;

                            echo '<div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Aksi <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/110/$user_id") .'" style="color:#a94442"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Ditolak</a></li>
                                      <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/100/$next_user").'" style="color:#3c763d"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Disetujui</a></li>
                                    </ul>
                                  </div>';

                            break;

                          case '400':
                            break;

                          case '205':

                            $permintaan = $this->db->get_where('sirkulasi_barang',array('jenis' => 'keluar','nomor_permintaan' => $permintaan_id));

                            if($permintaan->num_rows() == 0){
                              echo '<div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu">
                                        <li><a href="' . site_url("manage/sirkulasi-barang/keluar/add/" . $permintaan_id) .'" style="color:#3c763d"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> Barang keluar</a></li>
                                      </ul>
                                    </div>';

                            }

                            break;

                      }
                    }elseif ($jabatan === 'pimpinan') {

                    }
                  }

                }else{
                  //gudang asal
                  // echo 'test';
                  if($jenis === 'peminjaman')
                  {
                    if($jabatan === 'produksi')
                    {
                      switch ($row['status_kode']) {
                        //Menunggu tanggapan
                        case '500':

                          break;

                      }
                    }elseif ($jabatan === 'logistik') {
                      // echo $row['status_kode'];
                      // echo $jabatan;
                      // echo $row['current_user'];

                      switch ($row['status_kode']) {

                        //Menunggu tanggapan
                        case '210':
                          // $next_user = $user_id;
                          //
                          // echo '<div class="btn-group">
                          //         <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          //           Aksi <span class="caret"></span>
                          //         </button>
                          //         <ul class="dropdown-menu">
                          //           <li><a href="' . site_url("manage/permintaan_action/$permintaan_id/220/$next_user") .'" style="color:#8a6d3b"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Barang telah diterima</a></li>
                          //         </ul>
                          //       </div>';
                          break;

                        case '205':
                          echo 'test';
                          break;
                      }

                    }
                  }else{

                  }

                 } ?>
              </td>
          </tr>
        <?php } ?>
    </tbody>
</table>
<!-- MODAL PRODUK -->
<div class="modal col-lg-12 fade" id="dataitem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <form id="form-item" action="<?php echo site_url('manage/sirkulasi-barang/' . $kategori.'/add-detail')?>" method="post">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title" id="myModalLabel">Detail Barang</h4>
            </div>
            <div class="modal-body">
               <table width="100%" class="table table-bordered table-modal" id="table-modal" align="center">
                 <thead>
                    <tr>
                       <th>Kategori</th>
                       <th>Barang</th>
                       <th>Jumlah</th>
                       <th>Keterangan</th>
                    </tr>
                 </thead>
                 <tbody>
                 </tbody>
               </table>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>



    <?php if($kategori === 'keluar'){ ?>
    <script src="<?php echo site_url('assets/DataTables/extensions/Buttons/js/dataTables.buttons.min.js')?>"></script>
    <?php } ?>

    <script>

    $("#tanggal").datepicker({
        onSelect: function(dateText, inst) {
            var date = $(this).val();
            var time = $('#time').val();
            // alert('on select triggered');
            //$("#start").val(date + time.toString(' HH:mm').toString());
            $.ajax({
          		 url: '<?php echo site_url('manage/set_permintaan_filter/' . $kategori)?>',
          		 type: 'POST',
          		 data: {tgl_permintaan : date},
          		 success: function (data) {
                  //$('#table-modal').find("tr:gt(0)").remove();
                  //$('#table-modal').append(data);
                  <?php  if($user_level === 'pimpinan'){ ?>
                    //redirect('manage/permintaan');
                    window.location.href = "<?php echo site_url('manage/permintaan')?>";
                  <?php }else{ ?>
                    window.location.href = "<?php echo site_url('manage/permintaan/' . $kategori)?>";
                  <?php } ?>
          		 },
          		 error: function () {
          			 console.error('Error !');
          		 }

        	 });
        	 return false;

        }
    });

    function loaddetail(id){
  	  $.ajax({
    		 url: '<?php echo site_url('manage/detail_permintaan/')?>',
    		 type: 'GET',
    		 data: {id : id},
    		 success: function (data) {
            $('#table-modal').find("tr:gt(0)").remove();
            $('#table-modal').append(data);
    		 },
    		 error: function () {
    			 console.error('Error !');
    		 }

  	 });
  	  return false;
    }

    <?php if($kategori === 'keluar'){ ?>
      $('#example').DataTable( {
            dom: 'Bfrtip',
            ordering: false,
            buttons: [
                {
                    text: '+ Tambah Data',
                    action: function ( e, dt, node, config ) {
                        window.location = "<?php echo site_url('manage/permintaan/keluar/add')?>";
                    }
                }
            ]
        } );
    <?php }else{ ?>
      $('#example').DataTable( {
            dom: 'Bfrtip',
            ordering: false
        } );
    <?php } ?>


    </script>
