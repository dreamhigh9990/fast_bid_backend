<!DOCTYPE html> 
<html lang="en-US">
<head>
  	<title>Administration Panel</title>
  	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  	<!-- <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css"> -->
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<!-- Bootstrap 3.3.2 -->
	<!-- <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
	<!-- Font Awesome Icons -->
	<!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
	<!-- Ionicons -->
	<!-- <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" /> -->
	<!-- Theme style -->
	<!-- <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" /> -->
  <!-- daterange picker -->
  <!-- <link href="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" /> -->
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
	      page. However, you can choose any other skin. Make sure you
	      apply the skin class to the body tag so the changes take effect.
	-->
	<!-- <link href="<?php echo base_url(); ?>assets/dist/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" /> -->

  <!-- BEGIN PLUGIN CSS -->
  <link href="<?php echo base_url(); ?>assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
  <link href="<?php echo base_url(); ?>assets/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen"/>
  <link href="<?php echo base_url(); ?>assets/plugins/jquery-datatable/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen"/>
  <link href="<?php echo base_url(); ?>assets/plugins/boostrap-checkbox/css/bootstrap-checkbox.css" rel="stylesheet" type="text/css" media="screen"/>
  <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/plugins/dropzone/css/dropzone.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/plugins/ios-switch/ios7-switch.css" rel="stylesheet" type="text/css" media="screen">
  <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen"/>
  <link href="<?php echo base_url(); ?>assets/plugins/boostrap-slider/css/slider.css" rel="stylesheet" type="text/css"/>
  <!-- END PLUGIN CSS -->

  <!-- BEGIN CORE CSS FRAMEWORK -->
  <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
  <!-- <link href="<?php echo base_url(); ?>assets/plugins/morris/morris.css" rel="stylesheet" type="text/css" /> -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jquery-morris-chart/css/morris.css" type="text/css" media="screen">
  <!-- END CORE CSS FRAMEWORK -->

  <!-- BEGIN CSS TEMPLATE -->
  <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo base_url(); ?>assets/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
  <!-- END CSS TEMPLATE -->
</head>
<body class="">
  <!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse "> 
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
    <div class="header-seperation"> 
      <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">  
       <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" > <div class="iconset top-menu-toggle-white"></div> </a> </li>     
      </ul>
      <!-- BEGIN LOGO --> 
      <div class="user-info-wrapper"><h2 style="color:white">Admin</h2></div>
      <!-- END LOGO --> 
    </div>
    <!-- END RESPONSIVE MENU TOGGLER --> 
    <div class="header-quick-nav" >
      <!-- BEGIN TOP NAVIGATION MENU -->
      <div class="pull-left">
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="#" class="" id="layout-condensed-toggle" >
            <div class="iconset top-menu-toggle-dark"></div>
            </a> </li>
        </ul>
      </div>
      <!-- END TOP NAVIGATION MENU -->
      <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-right"> 
        <div class="chat-toggler">  
          <a href="#" class="dropdown-toggle" id="my-task-list" data-placement="bottom"  data-content='' data-toggle="dropdown" data-original-title="">
            <div class="user-details"> 
              <div class="username">
                <span class="bold">Administrator</span>
              </div>            
            </div> 
            <div class="iconset top-down-arrow"></div>
          </a>  
            <div id="notification-list" style="display:none">
              <div style="width:80px; height:30px">
                <div class="pull-center">
                  <a href="<?php echo base_url(); ?>admin/logout" class="btn btn-default">Sign out</a>
                </div>
              </div>
            </div>
          <div class="profile-pic"> 
            <img src="<?php echo base_url(); ?>assets/img/profiles/avatar.jpg"  alt="" data-src="<?php echo base_url(); ?>assets/img/profiles/avatar.jpg" data-src-retina="<?php echo base_url(); ?>assets/img/profiles/avatar.jpg" width="35" height="35" /> 
          </div>            
        </div>
      </div>
      <!-- END CHAT TOGGLER -->
    </div> 
    <!-- END TOP NAVIGATION MENU --> 
  </div>
  <!-- END TOP NAVIGATION BAR --> 
</div>
<!-- END HEADER --> 
<!-- BEGIN CONTAINER -->
<div class="page-container row"> 
  <!-- BEGIN SIDEBAR -->
  <div class="page-sidebar" id="main-menu"> 
    <!-- BEGIN MINI-PROFILE -->
    <div class="user-info-wrapper">  
      <div class="profile-wrapper">
        <img src="<?php echo base_url(); ?>assets/img/profiles/avatar.jpg"  alt="" data-src="<?php echo base_url(); ?>assets/img/profiles/avatar.jpg" data-src-retina="<?php echo base_url(); ?>assets/img/profiles/avatar2x.jpg" width="69" height="69" />
      </div>
      <div class="user-info">
        <br>
        <div class="greeting">Welcome</div>
        <div class="username">Administrator</div>
      </div>
    </div>
    <!-- END MINI-PROFILE -->
   
    <!-- BEGIN SIDEBAR MENU -->  
    <ul>
      <li <?php if($this->uri->segment(2) == 'residents'){echo 'class="start active"';}?>>
          <a href="<?php echo base_url(); ?>admin/residents">
              <i class="material-icons">question_answer</i>
              <span class="title">Residents</span>
          </a>
      </li>
    </ul>
    <a href="#" class="scrollup">Scroll</a>
    <div class="clearfix"></div>
    <!-- END SIDEBAR MENU --> 
  </div>
  <!-- END SIDEBAR --> 
