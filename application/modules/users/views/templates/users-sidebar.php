<!-- Side Navbar -->
<nav class="side-navbar">
   <!-- Sidebar Header-->
   <div class="sidebar-header d-flex align-items-center">
      <div class="avatar">
         <img src="<?php echo $user_dp = (isset($userdata->dp) && !empty($userdata->dp))?$userdata->dp:site_url().'/assets/uploads/avatar.png'; ?>" alt="profile picture" class="img-fluid rounded-circle">
      </div>
      <div class="title">
         <h1 class="h4"><?php  echo $user_name; ?></h1>
         <p><?php echo $role; ?></p>
      </div>
   </div>
   <ul class="list-unstyled">
      <li class="active"><a href="/dashboard"> <i class="fas fa-home"></i>Home </a></li>
      <li><a href="/activities"> <i class="fas fa-heart"></i> Profile Intresters </a></li>
      <li><a href="#"> <i class="fas fa-eye"></i>Profile Visitors </a></li>
      <li><a href="#"> <i class="fas fa-gift"></i>My Subscriptions </a></li>
      <li><a href="#"> <i class="fas fa-smile"></i>My Profile </a></li>
      <li><a href="#"> <i class="fas fa-file-powerpoint"></i>Plans </a></li>
      <li><a href="#"> <i class="far fa-play-circle"></i>Video Gallery </a></li>
      <li><a href="#"> <i class="fas fa-camera-retro"></i>Photo Gallery </a></li>
   </ul>
</nav>