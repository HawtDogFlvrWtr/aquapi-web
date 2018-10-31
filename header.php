<?php
define('INCLUDE_CHECK',true);
include 'config.php';
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>AquaPi - A aquarium controller geared around the Raspberry Pi</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A raspberry pi driven aqua controller interface" name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- third party css -->
        <link href="assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- third party css end -->

        <!-- App css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/animate.css" rel="stylesheet" type="text/css" />
        <!-- third party css -->
        <link href="assets/css/vendor/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <!-- third party css end -->



    </head>

    <body class="enlarged" data-keep-enlarged="true">

        <!-- Begin page -->
        <div class="wrapper">

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="slimscroll-menu">

                    <!--- Sidemenu -->
                    <ul class="metismenu side-nav">

                        <li class="side-nav-title side-nav-item">Navigation</li>

                        <li class="side-nav-item">
			<?php
                        if ($currentPage != 'guest.php') {
                        ?>
                            <a href="dashboard.php" class="side-nav-link">
			<?php } else { ?>
                            <a href="guest.php" class="side-nav-link">
			<?php } ?>
                                <i class="dripicons-meter"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>
			<?php 
			if ($currentPage != 'guest.php') {
			?>
                        <li class="side-nav-item">
                            <a href="modules.php" class="side-nav-link">
                                <i class="mdi mdi-power-socket-us"></i>
                                <span> Modules </span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="calendar.php" class="side-nav-link">
                                <i class="mdi mdi-calendar"></i>
                                <span> Calendar </span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="javascript: void(0);" class="right-bar-toggle side-nav-link">
                                <i class="mdi mdi-settings"></i>
                                <span> Settings </span>
                            </a>
                        </li>
			<?php } ?>

                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Topbar Start -->
                    <div class="navbar-custom">
			<ul style="max-height:70px;" class="list-unstyled topbar-right-menu float-right mb-0">
			 <li class="dropdown notification-list">
			   <a class="text-secondary nav-link dropdown-toggle arrow-none" href="#">
			   <i title="Current Temp" id="Temperature" class="noti-icon">...</i>
			   </a>
			 </li>
			 <li class="dropdown notification-list">
                           <a class="<?php if ($site_settings['light_override'] != 'resume'){ echo 'rubberBand animated infinite';}?> nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
			    <i id="light" title="Light Status" class="mdi mdi-lightbulb-on-outline noti-icon"></i>
			   </a>
                        <?php
                        if ($currentPage != 'guest.php') {
                        ?>
   			   <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
                                    <!-- item-->
                                    <div class=" dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Light Override</h6>
                                    </div>
				    <?php 
				    if ($site_settings['override'] == '' || $site_settings['override'] == 'resume') {
					$classModify = 'text-warning';
				    } else {
					$classModify = '';
				    }
				    ?>
                                    <a href="dashboard.php?override=resume" class="dropdown-item notify-item <?php echo $classModify;?>">
                                        <i class="mdi mdi-reload"></i>
                                        <span>Resume Schedule</span>
                                    </a>
				    <?php
					while($row = $lightMenuOverrideList->fetch_assoc()) {
					  if ($site_settings['override'] != "" && $site_settings['override'] == $row['value']) {
						$classModify = 'text-warning';
					  } else {
						$classModify = '';
					  }
				    ?>
                                    <!-- item-->
                                    <a href="dashboard.php?override=<?php echo $row['value'];?>" class="dropdown-item notify-item <?php echo $classModify;?>">
                                        <i class="mdi <?php echo $row['icon']?>"></i>
                                        <span><?php echo $row['type'];?></span>
                                    </a>
				    <?php } ?>

			   </div>
			<?php } ?>
			 </li>
			 <li class="dropdown notification-list">
                           <a class="nav-link" href="#"><i title="Pump Status" id="pump" class="mdi mdi-fan noti-icon"></i></a>
			 </li>
			 <li class="dropdown notification-list">
                           <a class="nav-link" title="Log Out" href="index.php?logout"><i id="logout" class="mdi mdi-logout noti-icon"></i></a>
			 </li>
			</ul>
                        <button class="button-menu-mobile open-left disable-btn">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </div>
		    <?php msgBoxDisplay();  ?>
                    <!-- end Topbar -->
