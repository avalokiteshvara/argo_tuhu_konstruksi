<?php

  $mode = $this->uri->segment(4);

  if($mode === 'edt' || $mode === 'edt_act'){

  }else{
    $jenis = $mode === 'add' ? '' : set_value('jenis');
    $tanggal = date('d-m-Y');
    $tgl_batas = $mode === 'add' ? date('d-m-Y') : set_value('tgl_batas');
    $tujuan_gudang = $mode === 'add' ? '' : set_value('tujuan_gudang');
    $nomor = $mode === 'add' ?  str_pad(($nomor_baru),10,'0',STR_PAD_LEFT) : set_value('nomor');
    $act = 'add-act';
  }

?>

<?php if(isset($msg['content'])){ ?>
<div id="alert-msg" class="<?php echo $msg['css_class']?>">
  <b>Warning!</b><br><?php echo $msg['content']?>
</div>
<?php } ?>

<form class="form-horizontal" action="<?php echo site_url('manage/permintaan/keluar/' . $act)?>" method="post">
  <div class="form-group">
    <label for="jenis" class="col-sm-2 control-label">Jenis</label>
    <div class="col-sm-5">
      <select name="jenis" id="jenis" class="select2" style="width:150px">
        <option value="pilih-jenis">Pilih Jenis</option>
        <option <?php echo $jenis === 'peminjaman' ? 'selected':'';?> value="peminjaman">Peminjaman</option>
        <option <?php echo $jenis === 'pembelian' ? 'selected':'';?> value="pembelian">Pembelian</option>
        <option <?php echo $jenis === 'pemakaian' ? 'selected':'';?> value="pemakaian">Pemakaian</option>
        <!-- <option <?php echo $jenis === 'pengembalian' ? 'selected':'';?> value="pengembalian">Pengembalian</option> -->
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="pemeriksa" class="col-sm-2 control-label">Permintaan</label>
    <div class="col-sm-5">
      <input type="text" class="form-control tanggal" id="tanggal" name="tanggal" value="<?php echo $tanggal?>">
    </div>
  </div>

  <div class="form-group" style="display:none" id="div_tgl_batas">
    <label for="pemeriksa" class="col-sm-2 control-label">Pengembalian</label>
    <div class="col-sm-5">
      <input type="text" class="form-control tanggal" id="tgl_batas" name="tgl_batas" value="<?php echo $tgl_batas?>">
    </div>
  </div>



  <div class="form-group">
    <label for="nomor" class="col-sm-2 control-label">Nomor</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="nomor" name="nomor" placeholder="" required="" value="<?php echo $nomor?>">
    </div>
  </div>

  <div class="form-group">
    <label for="jenis" class="col-sm-2 control-label">Tujuan</label>
    <div class="col-sm-5">
      <select name="tujuan_gudang" id="tujuan_gudang" class="select2" style="width:150px" required="">
      </select>
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
     <!-- <a href="<?php echo site_url('manage/permintaan/keluar');?>" class="btn btn-success" tabindex="11"><i class="fa fa-arrow-circle-left"></i> Kembali</a> -->
     <button onclick="window.history.back()" class="btn btn-success" tabindex="11"><i class="fa fa-arrow-circle-left"></i> Kembali</button>
     <button type="submit" class="btn btn-primary" tabindex="10"><i class="fa fa-check-circle"></i> Simpan</button>
  </div>
</form>

<!-- MODAL PRODUK -->
<div class="modal col-lg-12 fade" id="dataitem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <form id="form-item" action="<?php echo site_url('manage/permintaan/keluar/add-detail')?>" method="post">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title" id="myModalLabel">Data Item</h4>
            </div>
            <div class="modal-body">
               <table width="100%" class="table-form" align="center">
                  <tr>
                     <td>Barang</td>
                     <td>
                        <select id="barang_id" name="barang_id" required style="width: 100%" class="select2">
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
    $(".select2").select2();
  })

  <?php if($mode === 'add-act'){ ?>
    var v_jenis = $('#jenis').val();

    if (v_jenis !== 'pilih-jenis') {
       get_gudang(v_jenis);

    }else{
       $('#tujuan_gudang').html("");
    }
  <?php } ?>

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

 function get_gudang(v_jenis){
   $.get( "<?php echo site_url('manage/get_select_gudang')?>",
      {jenis : v_jenis }
   ).done(function(data){
      $('#tujuan_gudang').html(data);
   });
 }

 $('#jenis').change(function(){
     var v_jenis = $(this).val();

     if (v_jenis !== 'pilih-jenis')
     {

        if(v_jenis === 'peminjaman')
        {
          $('#div_tgl_batas').show();
        }else{
          $('#div_tgl_batas').hide();
        }

        get_gudang(v_jenis);
     }else{
        $('#tujuan_gudang').html("");
     }
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
          url: '<?php echo site_url("manage/permintaan/keluar/del-detail/")?>' + id,
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
