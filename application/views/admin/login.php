
<!DOCTYPE html> 
<html lang="en-US">
  <head>
    <title>Administration Panel</title>
    <meta charset="utf-8">
    <!-- <link href="http://learnaboutsam.org/mobileservice/assets/css/admin/global.css" rel="stylesheet" type="text/css"> -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <!-- <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- Font Awesome Icons -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- Theme style -->
    <!-- <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- iCheck -->
    <!-- <link href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" /> -->
    
    <!-- BEGIN CORE CSS FRAMEWORK -->
    <link href="<?php echo base_url(); ?>assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
    <!-- END CORE CSS FRAMEWORK -->
    <!-- BEGIN CSS TEMPLATE -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/css/magic_space.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
    <!-- END CSS TEMPLATE -->
  </head>
  <body class="error-body no-top lazy"  data-original="<?php echo base_url(); ?>assets/img/work.jpg"  style="background-image: url('<?php echo base_url(); ?>assets/img/work.jpg')">
    <div class="container">
      <div class="row login-container animated fadeInUp">  
        <div class="col-md-7 col-md-offset-2 tiles white no-padding">
          <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10"> 
            <h2 class="normal"><b>TWeBid</b> Administration</h2>
          </div>
          <div class="tiles grey p-t-20 p-b-20 text-black">
          <?php 
          $attributes = array('method' => 'post', 'id'=>'frm_register', 'class'=>'animated fadeIn');
          echo form_open('admin/login/validate_credentials', $attributes);
          ?>
            <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
              <div class="col-md-6 col-sm-6 ">
                <input name="user_name" id="user_name" type="text"  class="form-control" placeholder="Username">
              </div>
              <div class="col-md-6 col-sm-6">
                <input name="password" id="password" type="password"  class="form-control" placeholder="Password">
              </div>
            </div>
            <div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
              <div class="control-group  col-md-12">
                <div class="checkbox checkbox check-success">
<!--                   <input type="checkbox" id="checkbox1" value="1">
                  <label for="checkbox1">Keep me reminded </label>
 -->                  <button type="submit" class="btn btn-primary btn-cons pull-right" id="login_toggle">Login</button>
                      <button type="button" class="btn btn-primary btn-cons pull-right" id="signup_toggle">SignUp</button>
                </div>
              </div>
            </div>
          </form>
          </div>   
        </div>   
      </div>
    </div>

    <!-- jQuery 2.1.3 -->
    <!-- <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jQuery-2.1.3.min.js"></script> -->
    <!-- Bootstrap 3.3.2 JS -->
    <!-- <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> -->
    <!-- iCheck -->
    <!-- <script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js" type="text/javascript"></script> -->
    <!-- BEGIN CORE JS FRAMEWORK-->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-lazyload/jquery.lazyload.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/login_v2.js" type="text/javascript"></script>
    <!-- BEGIN CORE TEMPLATE JS -->

    <script>
      $(function () {
        // $('input').iCheck({
        //   checkboxClass: 'icheckbox_square-blue',
        //   radioClass: 'iradio_square-blue',
        //   increaseArea: '20%' // optional
        // });
        $('#signup_toggle').click(function(e){
          window.open('./signup', '_self');
        });
      });
    </script>
  </body>
</html>    
    