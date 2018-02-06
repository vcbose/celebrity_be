<body class="login-bg">
	<div class="header">
     <div class="container">
        <div class="row">
           <div class="col-md-12">
              <!-- Logo -->
              <div class="logo">
                 <h1><a href="index.html">Be Celebrity !</a></h1>
              </div>
           </div>
        </div>
     </div>
</div>

<div class="page-content container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-wrapper">
		        <div class="box">
		            <div class="content-wrap">
		                <h6>Sign In</h6>
		                <div class="social">
                            <a class="face_login" href="#">
                                <span class="face_icon">
                                    <img src="<?php echo asset_url('admin/images/facebook.png'); ?>" alt="fb">
                                </span>
                                <span class="text">Sign in with Facebook</span>
                            </a>
                            <div class="division">
                                <hr class="left">
                                <span>or</span>
                                <hr class="right">
                            </div>
                        </div>
                        <form action="../users/auth_user" method="post">
			                <input class="form-control" type="text" name="email" placeholder="E-mail address">
			                <input class="form-control" type="password" name="password" placeholder="Password">
			                <div class="action">
			                    <!-- <a class="btn btn-primary signup" href="index.html">Login</a> -->
			                    <input type="submit" class="btn btn-primary signup" value="Login" name="sigin">
			                </div> 
		                </form>               
		            </div>
		        </div>

		        <div class="already">
		            <p>Don't have an account yet?</p>
		            <a href="/users/registration">Sign Up</a>
		        </div>
		    </div>
		</div>
	</div>
</div>