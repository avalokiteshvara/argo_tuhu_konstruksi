
<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Kategori</th>
              <th>Gudang</th>
              <th>Piutang ( + )</th>
              <th>Hutang ( - )</th>
              <th>Stok Milik</th>
              <th>Tgl Update</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Kategori</th>
              <th>Gudang</th>
              <th>Piutang ( + )</th>
              <th>Hutang ( - )</th>
              <th>Stok Milik</th>
              <th>Tgl Update</th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($stok->result_array() as $row) { ?>
              <tr>
                  <td><?php echo str_pad(($row['id']),6,'0',STR_PAD_LEFT);?></td>
                  <td><?php echo $row['nama'];?></td>
                  <td><?php echo $row['kategori']?></td>
                  <td><?php echo $row['qty'] . ' ' . $row['satuan'];?></td>
                  <td><?php echo $row['qty_piutang'] . ' ' . $row['satuan'];?></td>
                  <td>
                    <?php if(abs($row['qty_hutang']) > 0){ ?>
                      <div class="alert alert-danger" role="alert" style="padding:1px">
                        <?php echo abs($row['qty_hutang']) . ' ' . $row['satuan'];?>
                      </div>
                    <?php }else{ ?>
                      <div class="alert alert-info" role="alert" style="padding:1px">
                        <?php echo abs($row['qty_hutang']) . ' ' . $row['satuan'];?>
                      </div>
                    <?php } ?>

                  </td>
                  <td>
                    <?php echo (intval($row['qty']) + intval($row['qty_piutang'])) - intval(abs($row['qty_hutang']))?>
                  </td>
                  <td><?php echo $row['updated_at'];?></td>

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
