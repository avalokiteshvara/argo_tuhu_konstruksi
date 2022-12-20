<?php
  $segment2 = $this->uri->segment(2,'permintaan');
  $active_menu = "";

  if($segment2 === 'permintaan'){

    $segment3 = $this->uri->segment(3,'masuk');
    $active_menu = 'permintaan-' . $segment3;

  }elseif($segment2 === 'sirkulasi-barang'){

    $segment3 = $this->uri->segment(3,'masuk');
    $active_menu = 'sirkulasi-barang-' . $segment3;

  }else{
    $active_menu = $segment2;
  }

  $jabatan = $this->session->userdata('user_jabatan');

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Sistem Inventaris PT.Argo Tuhu</title>
      <meta name="description" content="Source code generated using layoutit.com">
      <meta name="author" content="LayoutIt!">

      <style>
        /* tambahan */
        .table-form, .table-form tr, .table-form td, .table-form th {
          padding: 5px 10px;
        }
        .table-form { margin-bottom: -10px}

      </style>

      <link href="<?php echo site_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
      <link href="<?php echo site_url('assets/css/style.css')?>" rel="stylesheet">
      <link href="<?php echo site_url('assets/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet">

<?php
  if(isset($css_files)){
    foreach($css_files as $file): ?>
    	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
  	<?php endforeach;
  }else{ ?>
      <link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css');?>" />
      <link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/DataTables/media/css/jquery.dataTables.css'); ?>" />
      <link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/DataTables/extensions/Buttons/css/buttons.dataTables.min.css'); ?>" />
<?php }; ?>

<?php
  if(isset($js_files)){
    foreach($js_files as $file): ?>
      <script src="<?php echo $file; ?>"></script>
    <?php endforeach;
  }else{ ?>
      <script src="<?php echo site_url('assets/js/jquery.min.js')?>"></script>
      <script src="<?php echo site_url('assets/DataTables/media/js/jquery.dataTables.min.js')?>"></script>

      <script src="<?php echo site_url('assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js')?>"></script>
<?php }; ?>

    <!-- select2 -->
    <link href="<?php echo site_url('assets/select2/dist/css/select2.css')?>" rel="stylesheet">

    <script src="<?php echo site_url('assets/select2/dist/js/select2.full.js')?>"></script>
    <script src="<?php echo site_url('assets/select2/dist/js/i18n/id.js')?>"></script>

    <!-- <script src="<?php echo site_url('assets/js/jquery.min.js');?>"></script> -->
    <script src="<?php echo site_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo site_url('assets/js/scripts.js');?>"></script>

   </head>
   <body>

      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <nav class="navbar navbar-default" role="navigation" style="margin-top:10px">
                  <div class="navbar-header">
                     <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                     <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                   </button> <a href="<?php echo site_url('manage')?>" class="navbar-brand" href="#">Sistem Inventaris</a>
                  </div>
                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                     <ul class="nav navbar-nav navbar-right">
                       <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo strtoupper($this->session->userdata('user_username')); ?><strong class="caret"></strong></a>
                          <ul class="dropdown-menu">
                             <li>
                                <a href="<?php echo base_url() . 'manage/profile'?>">Profile</a>
                             </li>

                             <li class="divider">
                             </li>
                             <li>
                                <a href="<?php echo base_url() . 'signout'?>">Keluar Aplikasi</a>
                             </li>
                          </ul>
                       </li>
                     </ul>
                  </div>
               </nav>
               <!-- <ul class="breadcrumb">
                  <li>
                     <a href="#">Home</a> <span class="divider"></span>
                  </li>
                  <li>
                     <a href="#">Library</a> <span class="divider"></span>
                  </li>
                  <li class="active">
                     Data
                  </li>
               </ul> -->
               <div class="row">
                  <div class="col-md-10">
                     <div class="panel panel-default">
                        <div class="panel-heading">
                           <h3 class="panel-title" contenteditable="true">

                           </h3>
                        </div>
                        <div class="panel-body">
                          <?php if(isset($output)){ echo $output; }else{ include $page_name . ".php";} ?>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-2">

                      <!-- MASTER MENU -->
                      <div class="list-group">
                        <div class="list-group-item active">Master</div>
                        <a href="<?php echo site_url('manage/satuan-barang')?>" <?php echo in_array($active_menu,array('satuan-barang')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                          Satuan Barang
                        </a>


                        <a href="<?php echo site_url('manage/gudang')?>" <?php echo in_array($active_menu,array('gudang')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                           Gudang
                        </a>


                        <a href="<?php echo site_url('manage/barang')?>" <?php echo in_array($active_menu,array('barang')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                           Barang
                        </a>


                        <a href="<?php echo site_url('manage/users')?>" <?php echo in_array($active_menu,array('users')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                           Akun User
                        </a>

                     </div>




                      <?php if($jabatan === 'pimpinan'){ ?>
                        <div class="list-group">
                          <div class="list-group-item active">Transaksi</div>
                          <a href="<?php echo site_url('manage/permintaan')?>" <?php echo in_array($active_menu,array('permintaan-masuk')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                            Permintaan <!-- <span class="badge">14</span> -->
                          </a>
                        </div>

                      <?php }else{ ?>

                        <div class="list-group">
                          <div class="list-group-item active">Transaksi</div>
                          <a href="<?php echo site_url('manage/permintaan/masuk')?>" <?php echo in_array($active_menu,array('permintaan-masuk')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                            Permintaan Masuk <!-- <span class="badge">14</span> -->
                          </a>

                          <?php if(in_array($jabatan,array('produksi'))){ ?>
                          <a href="<?php echo site_url('manage/permintaan/keluar')?>" <?php echo in_array($active_menu,array('permintaan-keluar')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                            Permintaan Keluar <!-- <span class="badge">14</span> -->
                          </a>
                          <?php } ?>
                        </div>

                      <?php } ?>



                      <?php if($jabatan === 'logistik'){ ?>

                        <div class="list-group">
                          <div class="list-group-item active">Sirkulasi Barang & Stok</div>
                          <a href="<?php echo site_url('manage/sirkulasi-barang/masuk')?>" <?php echo in_array($active_menu,array('sirkulasi-barang-masuk')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                            Barang Masuk
                          </a>

                          <a href="<?php echo site_url('manage/sirkulasi-barang/keluar')?>" <?php echo in_array($active_menu,array('sirkulasi-barang-keluar')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                            Barang Keluar
                          </a>

                          <a href="<?php echo site_url('manage/stok')?>" <?php echo in_array($active_menu,array('stok')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                            Stok
                          </a>

                          <a href="<?php echo site_url('manage/stok-opname')?>" <?php echo in_array($active_menu,array('stok-opname')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                            Stok Opname
                          </a>

                        </div>

                        <div class="list-group">
                          <div class="list-group-item active">Status Pinjam</div>
                          <a href="<?php echo site_url('manage/jadwal_pengembalian')?>" <?php echo in_array($active_menu,array('sirkulasi-barang-masuk')) ? 'style="background-color:#f5f5f5"' : ''?> class="list-group-item">
                            Pengembalian barang
                          </a>

                        </div>

                      <?php }?>






                  </div>


               </div>
            </div>
            <footer class="footer">
               <div class="container">
                  <center>
                     <p class="text-muted">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
                  </center>
               </div>
            </footer>
         </div>
      </div>

      <script>

        $('.reset-password-icon').on('click',function(){
          return confirm('Apakah anda yakin ingin mereset password user ini?')
        });

        $(document).ready(function () {

          // $(".select2").select2();

    			$(function() {
    				$( ".tanggal" ).datepicker({
    					changeMonth: true,
    					changeYear: true,
    					dateFormat: 'dd-mm-yy'
    				});
    			});
        });


      </script>
   </body>
</html>
