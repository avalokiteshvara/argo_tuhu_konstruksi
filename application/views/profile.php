<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">
         Form Profile
      </h3>
   </div>
   <div class="panel-body">
     <?php if(isset($msg['content'])){ ?>
     <div id="alert-msg" class="<?php echo $msg['css_class']?>">
       <?php echo $msg['content']?>
     </div>
     <?php } ?>
     <form action="" method="post">
       <div class="form-group">
         <label>Username</label>
         <input type="text" class="form-control" placeholder="Username" readonly="" value="<?php echo $p->username?>">
       </div>

       <div class="form-group">
         <label>Level</label>
         <input type="text" class="form-control" placeholder="Level" readonly="" value="<?php echo strtoupper($p->level)?>">
       </div>

       <div class="form-group">
         <label>Nama Lengkap</label>
         <input type="text" class="form-control" placeholder="Nama lengkap" name="nama_lengkap" value="<?php echo $p->nama_lengkap?>">
       </div>

       <div class="form-group">
         <label>Telepon</label>
         <input type="text" class="form-control" placeholder="Telepon" name="telepon" value="<?php echo $p->telepon?>">
       </div>

       <div class="form-group">
         <label>Password Lama</label>
         <input type="password" class="form-control" placeholder="Password Lama" id="pass_lama" name="pass_lama">
         <span class="help-block">* Kosongkan jika tidak ingin merubah password</span>
       </div>

       <div class="form-group" id="div_pass_baru">
         <label>Password Baru</label>
         <input type="password" class="form-control" placeholder="Password Baru" id="pass_baru" name="pass_baru">
       </div>

       <div class="form-group" id="div_pass_ulangi">
         <label>Ulangi</label>
         <input type="password" class="form-control" placeholder="Ulangi Password" id="pass_ulangi" name="pass_ulangi">
       </div>

       <button type="submit" class="btn btn-default">Submit</button>
     </form>
   </div>
</div>

<script>
    $('#div_pass_baru , #div_pass_ulangi').hide();


    $( "#pass_lama").keyup(function() {
      if($(this).val() != ''){
        $('#div_pass_baru , #div_pass_ulangi').show();
      }else{
        $('#div_pass_baru , #div_pass_ulangi').hide();
      }
    });
</script>
