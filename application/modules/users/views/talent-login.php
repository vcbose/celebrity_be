<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <title>
            Celebrity be!
        </title>
        <meta content="" name="description">
            <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
                <meta content="all,follow" name="robots">
                    <!-- Bootstrap CSS-->
                    <link href="<?php echo asset_url('website/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet'); ?>">
                        <!-- Font Awesome CSS-->
                        <link href="<?php echo asset_url('website/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet'); ?>">
                            <!-- Fontastic Custom icon font-->
                            <link href="<?php echo asset_url('website/css/fontastic.css'); ?>" rel="stylesheet">
                                <!-- Google fonts - Poppins -->
                                <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,700" rel="stylesheet">
                                    <!-- theme stylesheet-->
                                    <!-- Custom stylesheet - for your changes-->
                                    <link href="<?php echo asset_url('website/css/custom.css') ?>" rel="stylesheet">
                                        <!-- Favicon-->
                                        <link href="<?php echo asset_url('website/img/fav-icon.png') ?>" rel="shortcut icon">
                                        </link>
                                    </link>
                                </link>
                            </link>
                        </link>
                    </link>
                </meta>
            </meta>
        </meta>
    </head>

    <body class="login-page">
        <div class="login-container">
            <div class="login-header login-caret">
                <div class="login-content">
                    <h1>
                        Celebrity Be
                    </h1>
                    <p class="description">
                        Dear user, log in to access the admin area!
                    </p>
                </div>
            </div>
            <form action="../users/auth_user" method="post">
            <!-- login-header login-caret-->
            <div class="login-form">
                <div class="login-contentn">
                    <div class="input-group input-group-cs margin-bottom-20">
                        <div class="input-group-prepend">
                            <div class="input-group-text custm-icon">
                                <i class="fa fa-user">
                                </i>
                            </div>
                        </div>
                        <!-- <input class="form-control custom-formstyle" id="inlineFormInputGroupUsername" placeholder="Username" type="text">
                        </input> -->
                        <input class="form-control custom-formstyle" type="text" name="email" placeholder="Username / Email">
                    </div>
                    <div class="input-group input-group-cs">
                        <div class="input-group-prepend">
                            <div class="input-group-text custm-icon">
                                <i class="fa fa-unlock-alt">
                                </i>
                            </div>
                        </div>
                        <!-- <input class="form-control custom-formstyle" id="inputPassword3" placeholder="Password" type="password">
                        </input> -->
                        <input class="form-control custom-formstyle" type="password" name="password" placeholder="Password">
                    </div>

                    <!-- <button class="btn btn-block btn-login custom-formstyle" type="submit">
                        Login In
                        <i class="fa fa-unlock-alt" style="float:right;">
                        </i>
                    </button> -->

                    <button type="submit" class="btn btn-block btn-login custom-formstyle" value="Log In" name="sigin">
                    	Log In
                    	<i class="fa fa-unlock-alt" style="float:right;"></i>
                   	</button>

                    <div class="login-bottom-links">
                        <a class="link" href="#">
                            Forgot your password?
                        </a>
                        <br>
                            <a href="#" style="font-size: 13px;color: #85868e;">
                                Privacy Policy
                            </a>
                        </br>
                    </div>
                </div>
                <!-- input-group-->
            </div>
        	</form>
            <!-- login-content-->
        </div>
        <!-- login-form-->
    </body>
</html>
<!-- login-container-->
<!-- Javascript files-->
<script src="https://code.jquery.com/jquery-3.2.1.min.js">
</script>
<script src="<?php echo asset_url('website/vendor/popper.js/umd/popper.min.js'); ?>">
</script>
<script src="<?php echo asset_url('website/vendor/bootstrap/js/bootstrap.min.js'); ?>">
</script>
<script src="<?php echo asset_url('website/vendor/jquery.cookie/jquery.cookie.js'); ?>">
</script>
<script src="<?php echo asset_url('website/vendor/jquery-validation/jquery.validate.min.js'); ?>">
</script>
<!-- Main File-->
<script src="<?php echo asset_url('website/js/front.js') ?>">
</script>
