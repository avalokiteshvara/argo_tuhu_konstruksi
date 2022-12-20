
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Silahkan Login</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url()?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo base_url()?>assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url()?>assets/css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo base_url()?>assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo site_url('assets/js/jquery.min.js')?>"></script>
    <script src="<?php echo site_url('assets/js/jquery-ui.min.js')?>"></script>
    <script>
      ;(function($, window, document, undefined) {

      	$.fn.animatedBG = function(options){
      		var defaults = {
      				colorSet: ['#abebfe', '#aad667', '#57e6ee', '#ff7ebb'],
      				speed: 4000
      			},
      			settings = $.extend({}, defaults, options);

      		return this.each(function(){
      			var $this = $(this);

      			$this.each(function(){
      				var $el = $(this),
      					colors = settings.colorSet;

      				function shiftColor() {
      					var color = colors.shift();
      					colors.push(color);
      					return color;
      				}

      				// initial color
      				var initColor = shiftColor();
      				$el.css('backgroundColor', initColor);
      				setInterval(function(){
      					var color = shiftColor();
      					$el.animate({backgroundColor: color}, 3000);
      				}, settings.speed);
      			});
      		});
      	};

      	// Initialize
      	$(function(){
      		$('.animated_bg').animatedBG();
      	});

      }(jQuery, window, document));
    </script>

  </head>

  <body class="animated_bg">

    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <!-- <h1 class="text-center login-title" style="margin-top:100px">Silahkan Login </h1> -->
                <div class="account-wall" style="margin-top:50px">


                    <img class="profile-img" src="<?php echo base_url() . 'assets/images/logo.png'?>" alt="">
                    <?php if(isset($msg['content'])){ ?>
                    <div id="alert-msg" class="<?php echo $msg['css_class']?>">
                      <b>Warning!</b><br><?php echo $msg['content']?>
                    </div>
                    <?php } ?>
                    <form class="form-signin" method="post" action="">
                      <input type="text" class="form-control" placeholder="Username" name="username" required autofocus style="margin-bottom:10px">
                      <input type="password" class="form-control" placeholder="Password" name="password" required>
                      <button class="btn btn-lg btn-primary btn-block" type="submit">Masuk</button>
                      <!-- <label class="checkbox pull-left">
                          <input type="checkbox" value="remember-me">
                          Remember me
                      </label> -->
                      <!-- <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span> -->
                    </form>
                </div>
                <!-- <a href="#" class="text-center new-account">Create an account </a> -->
            </div>
        </div>
    </div>


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo base_url()?>assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
