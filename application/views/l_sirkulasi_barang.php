
<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
              <th>Tanggal</th>
              <th>Nomor</th>
              <th>Permintaan</th>
              <th>Diperiksa</th>
              <th>Catatan</th>
              <th>Detail</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>Tanggal</th>
              <th>Nomor</th>
              <th>Permintaan</th>
              <th>Diperiksa</th>
              <th>Catatan</th>
              <th>Detail</th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($recordset->result_array() as $row) { ?>
              <tr>
                  <td><?php echo $row['tgl'];?></td>
                  <td><?php echo $row['nomor_sirkulasi'];?></td>
                  <td><?php echo str_pad(($row['nomor_permintaan']),6,'0',STR_PAD_LEFT);?></td>
                  <td><?php echo $row['pemeriksa'];?></td>
                  <td><?php echo $row['catatan'];?></td>
                  <td>
                     <a href="#dataitem"  onclick="loaddetail('<?php echo $row['id']?>')" data-toggle="modal" class="btn btn-info btn-sm" style="margin-bottom: 10px"><i class="fa fa-eye"> </i> Detail Barang</a>
                  </td>
              </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- MODAL PRODUK -->
    <div class="modal col-lg-12 fade" id="dataitem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <form id="form-item" action="<?php echo site_url('manage/sirkulasi-barang/' . $jenis.'/add-detail')?>" method="post">
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


    <script src="<?php echo site_url('assets/DataTables/extensions/Buttons/js/dataTables.buttons.min.js')?>"></script>



    <script>

    function loaddetail(id){
  	  $.ajax({
    		 url: '<?php echo site_url('manage/sirkulasi-barang/' . $jenis . '/detail')?>',
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

    $('#example').DataTable( {
          dom: 'Bfrtip',
          ordering: false,
          buttons: [
              {
                  text: '+ Tambah Data',
                  action: function ( e, dt, node, config ) {
                      window.location = "<?php echo $jenis;?>/add";
                  }
              }
          ]
      } );

    </script>
