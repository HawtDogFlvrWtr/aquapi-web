<!DOCTYPE html>
<?php
define('INCLUDE_CHECK',true);
include 'config.php';
include 'functions.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>AquaPi - A aquarium controller geared around the Raspberry Pi</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="authentication-bg">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card">

                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <a href="index.php">
                                    <span><img src="assets/images/logo_sm.png" alt="" height="24"></span>
                                </a>
                            </div>

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Sign In</h4>
                                    <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>
                                </div>

                                <form action="index.php" method="post" >

                                    <div class="form-group">
                                        <label for="emailaddress">Email address</label>
                                        <input name="email" class="form-control" type="email" id="emailaddress" required="" placeholder="Enter your email">
                                    </div>

                                    <div class="form-group">
<!--                                        <a href="pages-recoverpw.html" class="text-muted float-right"><small>Forgot your password?</small></a>
-->
                                        <label for="password">Password</label>
                                        <input name="password" class="form-control" type="password" required="" id="password" placeholder="Enter your password">
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                            <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                        </div>
				    </div>
				    <div class="form-group mb-0 text-center">
					<button class="btn btn-primary" type="submit"> Log In </button>
				    </div>
                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->


                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <footer class="footer footer-alt">
            2018 © AquaPi
        </footer>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
