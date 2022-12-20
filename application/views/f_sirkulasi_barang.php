<?php
  $jenis = $this->uri->segment(3);
  $mode = $this->uri->segment(4);

  $user_level = $this->session->userdata('user_level');
  $gudang_id = 0;

  if($user_level !== 'pimpinan'){
    $gudang_id = $this->session->userdata('user_gudang');
  }



  if($mode === 'edt' || $mode === 'edt_act'){

  }else{

    $pembuat = $this->session->userdata('user_namalengkap');
    $tanggal = date('d-m-Y');

    // $nomor_permintaan = ;

    if($jenis === 'masuk'){
      $nomor = $mode === 'add' ?  'BM-' . str_pad(($nomor_baru),10,'0',STR_PAD_LEFT) : set_value('nomor');

    }else{
      $nomor = $mode === 'add' ?  'BK-' . str_pad(($nomor_baru),10,'0',STR_PAD_LEFT) : set_value('nomor');
    }

    $nomor_permintaan = $mode === 'add' ? $this->uri->segment(5,'') : set_value('nomor_permintaan');
    $catatan = $mode === 'add' ? '' : set_value('catatan');

    //jika $nomor_permintaan > 0 maka tampilkan detail



    $act = 'add_act';
 }
?>

<?php if(isset($msg['content'])){ ?>
<div id="alert-msg" class="<?php echo $msg['css_class']?>">
  <b>Warning!</b><br><?php echo $msg['content']?>
</div>
<?php } ?>

<form class="form-horizontal" action="<?php echo site_url('manage/sirkulasi-barang/' . $jenis . '/' . $act)?>" method="post">

  <input type="hidden" name="pengembalian" value="<?php echo $this->uri->segment(6,'N')?>" />

  <div class="form-group">
    <label for="jenis" class="col-sm-2 control-label">Jenis</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="jenis" name="jenis" placeholder="<?php echo 'BARANG ' . strtoupper($jenis)?>" readonly="">
    </div>
  </div>
  <div class="form-group">
    <label for="pembuat" class="col-sm-2 control-label">Pembuat</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="pembuat" name="pembuat" placeholder="<?php echo strtoupper($pembuat)?>" readonly="">
    </div>
  </div>
  <div class="form-group">
    <label for="pemeriksa" class="col-sm-2 control-label">Tanggal</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="<?php echo $tanggal?>" readonly="">
    </div>
  </div>
  <div class="form-group">
    <label for="nomor" class="col-sm-2 control-label">Nomor</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="nomor" name="nomor" value="<?php echo $nomor;?>" placeholder="" required="">
    </div>
  </div>
  <div class="form-group">
    <label for="nomor" class="col-sm-2 control-label">No. Permintaan</label>
    <div class="col-sm-5">
      <!-- <input type="text" class="form-control" id="nomor_permintaan" name="nomor_permintaan" value="<?php echo $nomor_permintaan;?>" placeholder="" required=""> -->
      <?php
        $segment_5 = $this->uri->segment(5,0);
      ?>

      <?php if($segment_5 > 0){ ?>
        <select class="form-control" id="nomor_permintaan" name="nomor_permintaan" required="">
          <option value="<?php echo $segment_5;?>"><?php echo str_pad(($segment_5),6,'0',STR_PAD_LEFT)?></option>
        </select>
      <?php }else{ ?>
        <select class="form-control" id="nomor_permintaan" name="nomor_permintaan" required="">
          <option value="">Pilih Nomor Permintaan</option>
          <?php

            function sudah_sirkulasi($permintaan_id,$jenis){
              $ci =& get_instance();
              $ret = $ci->db->get_where('sirkulasi_barang',array('jenis' => $jenis,'nomor_permintaan' => $permintaan_id));
              return $ret->num_rows();
            }

            if($jenis === 'masuk'){
              $this->db->where_in('jenis',array('peminjaman','pembelian','pengembalian'));
              $this->db->where_not_in('status',array('110'));
              $this->db->where('dari_gudang',$gudang_id);
              $permintaan = $this->db->get_where('permintaan');
              foreach ($permintaan->result_array() as $p) { ?>
                <?php if(sudah_sirkulasi($p['id'],'masuk') == 0){ ?>
                <option <?php echo $nomor_permintaan == $p['id'] ? 'selected':''?>  value="<?php echo $p['id']?>"><?php echo str_pad(($p['id']),6,'0',STR_PAD_LEFT)?></option>
                <?php } ?>
              <?php }
            }else{
              $segment_5 = $this->uri->segment(5,0);

              $this->db->where_in('jenis',array('peminjaman','pengembalian','pemakaian'));
              $this->db->where_not_in('status',array('110'));
              $this->db->where('tujuan_gudang',$gudang_id);
              $permintaan = $this->db->get_where('permintaan');
              foreach ($permintaan->result_array() as $p) { ?>
                <?php if(sudah_sirkulasi($p['id'],'keluar') == 0){ ?>
                <option <?php echo $nomor_permintaan == $p['id'] ? 'selected':''?> value="<?php echo $p['id']?>"><?php echo str_pad(($p['id']),6,'0',STR_PAD_LEFT)?></option>
                <?php } ?>
              <?php }
            }


          ?>
        </select>
      <?php } ?>


    </div>
  </div>
  <div class="form-group">
    <label for="catatan" class="col-sm-2 control-label">Catatan</label>
    <div class="col-sm-5">
      <textarea name="catatan" id="catatan" cols="68" rows="5"><?php echo $catatan?></textarea>
    </div>
  </div>

  <div class="col-lg-12" style="margin-bottom: 20px">
     <a href="#dataitem"  data-toggle="modal" class="btn btn-info btn-sm pull-right" style="margin-bottom: 10px"><i class="fa fa-plus"> </i> Tambah Item</a>
  </div>
  <div class="col-lg-12">
     <div class="table-responsive">
        <table  id="table-body" class="table table-bordered table-hover">
           <thead>
              <tr>
                 <th>Kategori</th>
                 <th>Barang</th>
                 <th>Jumlah</th>
                 <th>Keterangan</th>
                 <th style="width: 8%">Aksi</th>
              </tr>
           </thead>
           <tbody>
             <?php if(count($this->sesitem->contents()) == 0){ ?>
               <tr>
                  <td colspan="5" style="text-align: center; font-weight: bold">
                     <div class="alert alert-danger" style="margin-bottom: 0px">Data Item Belum Ada</div>
                  </td>
               </tr>
             <?php }else{
               $table_row = "";
               foreach ($this->sesitem->contents() as $item) {
                   $barang = $this->db->get_where('barang',array('id' => $item['barang_id']))->row();
                   $satuan = $this->db->get_where('satuan',array('id' => $barang->satuan_id))->row();
                   $nama = $barang->nama;
                   $kategori = ucwords($barang->kategori);
                   $nama_satuan = $satuan->nama;

                   $table_row .= "<tr id='row_".$item['rowid']."'>
                                   <td>".$kategori.'</td>
                                   <td>'.$nama.'</td>
                                   <td>'.$item['qty'] . ' ' . $nama_satuan .'</td>
                                   <td>'.$item['keterangan']."</td>
                                   <td>
                                     <div class=\"btn-group\">
                                        <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"del('".$item['rowid']."')\"><i class=\"fa fa-trash-o\"></i> Hapus</button>
                                     </div>
                                   </td>
                                 </tr>";
               }

               echo $table_row;

             } ?>

           </tbody>
        </table>
     </div>
  </div>
  <div class="col-lg-12 pull-right">
     <!-- <a href="<?php echo site_url('manage/sirkulasi-barang/' . $jenis);?>" class="btn btn-success" tabindex="11"><i class="fa fa-arrow-circle-left"></i> Kembali</a> -->
     <button onclick="window.history.back()" class="btn btn-success" tabindex="11"><i class="fa fa-arrow-circle-left"></i> Kembali</button>
     <button type="submit" class="btn btn-primary" tabindex="10"><i class="fa fa-check-circle"></i> Simpan</button>
  </div>
</form>

<!-- MODAL PRODUK -->
<div class="modal col-lg-12 fade" id="dataitem" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <form id="form-item" action="<?php echo site_url('manage/sirkulasi-barang/' . $jenis.'/add-detail')?>" method="post">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title" id="myModalLabel">Data Item</h4>
            </div>
            <div class="modal-body">
               <table width="100%" class="table-form" align="center">
                  <tr>
                     <td>Barang</td>
                     <td>
                        <select id="barang_id" name="barang_id" required style="width: 100%">
                            <option value="">[ Pilih Barang ]</option>
                            <optgroup label="Barang">
                            <?php
                            $bahan = $this->db->get_where('barang',array('kategori' => 'bahan'));
                            foreach ($bahan->result_array() as $b) { ?>
                              <option value="<?php echo $b['id']?>"><?php echo ucwords($b['nama'])?></option>
                            <?php }  ?>
                            </optgroup>

                            <optgroup label="Alat">
                            <?php
                            $alat = $this->db->get_where('barang',array('kategori' => 'alat'));
                            foreach ($alat->result_array() as $a) { ?>
                              <option value="<?php echo $a['id']?>"><?php echo ucwords($a['nama'])?></option>
                            <?php }  ?>
                            </optgroup>
                          </select>
                     </td>
                  </tr>

                  <tr>
                     <td width="40%">Kuantitas</td>
                     <td><input type="number" min="1" id="qty" name="qty" class="form-control col-lg-12" required value="1"></td>
                  </tr>
                  <tr>
                     <td width="40%">Keterangan</td>
                     <td><textarea name="keterangan" id="keterangan" style="width:100%"></textarea></td>
                  </tr>
               </table>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Tambahkan</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>


  $(document).ready(function () {
    $("#nomor_permintaan").select2({
      minimumInputLength:5
    });

    $("#barang_id").select2();
  })

  $('#form-item').submit(function(){

    var page_url = $(this).attr("action");
    $.ajax({
       url: page_url,
       type: 'POST',
       data: $('#form-item').serialize(),
       success: function (data) {
              $('#table-body').find("tr:gt(0)").remove();
              $('#table-body').append(data);
              $('#dataitem').modal('hide');
       },
       error: function () {
         console.error('Error !');
       }

   });
    return false;
 });

 function del(id){

    var answer =  confirm('Anda yakin ingin menghapus data ini?');
    var empty_row =  '<tr>' +
                     '    <td colspan="5" style="text-align: center; font-weight: bold">' +
                     '      <div class="alert alert-danger" style="margin-bottom: 0px">Data Item Belum Ada</div>' +
                     '    </td>' +
                     '</tr>';
    if (answer) {
        $.ajax({
          type:'POST',
          async: false,
          cache: false,
          url: '<?php echo site_url("manage/sirkulasi-barang/" . $jenis ."/del-detail/")?>' + id,
          success: function(data){
              var tr  = $('#row_' + id);
              tr.css("background-color","").css("background-color","#FF3700");
              tr.fadeOut(400, function(){
                 tr.remove();

                 if (data == 0) {
                   $('#table-body').find("tr:gt(0)").remove();
                   $('#table-body').append(empty_row);
                 }else{
                   $('#total').html(data);
                 }
              });


          }
        });
    }
 }
</script>
