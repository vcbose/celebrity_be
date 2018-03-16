<div class="page">
<!-- Main Navbar-->
<header class="header">
   <nav class="navbar">
      <!-- Search Box-->
      <div class="container-fluid">
         <div class="navbar-holder d-flex align-items-center justify-content-between">
            <!-- Navbar Header-->
            <div class="navbar-header">
               <!-- Navbar Brand -->
               <a href="index.html" class="navbar-brand">
                  <div class="brand-text brand-big"> Celebrity Be </div>
                  <div class="brand-text brand-small"><strong>CB</strong></div>
               </a>
               <!-- Toggle Button--><a id="toggle-btn" href="#" class="menu-btn active"><span></span><span></span><span></span></a>
            </div>
            <!-- Navbar Menu -->
            <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
               <!-- Notifications-->
               <li class="nav-item dropdown">
                  <a id="notifications" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="fas fa-bell"></i><span class="badge bg-red">12</span></a>
                  <ul aria-labelledby="notifications" class="dropdown-menu">
                     <li>
                        <a rel="nofollow" href="#" class="dropdown-item">
                           <div class="notification">
                              <div class="notification-content"><i class="fa fa-envelope bg-green"></i>You have 6 new messages </div>
                              <div class="notification-time"><small>4 minutes ago</small></div>
                           </div>
                        </a>
                     </li>
                     <li>
                        <a rel="nofollow" href="#" class="dropdown-item">
                           <div class="notification">
                              <div class="notification-content"><i class="fa fa-twitter bg-blue"></i>You have 2 followers</div>
                              <div class="notification-time"><small>4 minutes ago</small></div>
                           </div>
                        </a>
                     </li>
                     <li>
                        <a rel="nofollow" href="#" class="dropdown-item">
                           <div class="notification">
                              <div class="notification-content"><i class="fa fa-upload bg-orange"></i>Server Rebooted</div>
                              <div class="notification-time"><small>4 minutes ago</small></div>
                           </div>
                        </a>
                     </li>
                     <li>
                        <a rel="nofollow" href="#" class="dropdown-item">
                           <div class="notification">
                              <div class="notification-content"><i class="fa fa-twitter bg-blue"></i>You have 2 followers</div>
                              <div class="notification-time"><small>10 minutes ago</small></div>
                           </div>
                        </a>
                     </li>
                     <li><a rel="nofollow" href="#" class="dropdown-item all-notifications text-center"> <strong>view all notifications                                            </strong></a></li>
                  </ul>
               </li>
               <!-- Logout    -->
               <li class="nav-item"><a href="/logout" class="nav-link logout">Logout<i class="fas fa-arrow-left"></i></a></li>
            </ul>
         </div>
      </div>
   </nav>
</header>
<div class="page-content d-flex align-items-stretch">

<?php require_once('templates/users-sidebar.php') ?>

<div class="content-inner">
<!-- Page Header-->
<header class="page-header">
   <div class="container-fluid">
      <h2 class="no-margin-bottom">Home</h2>
   </div>
</header>
<!-- Dashboard Counts Section-->
<section class="dashboard-counts no-padding-bottom">
<div class="container-fluid">
   <div class="row bg-white has-shadow" style="    background: #eaeaea !important;">
    <?php
    $a_noti_count = array();
    $visits = 0;
    $interests = 0;
    foreach ($notifications as $key => $noti_details) {

        if( isset($noti_details['feature']['Visits']) && $noti_details['feature']['Visits'] == 1 ){
            $a_noti_count['visits'] = $visits++;
        }

        if( isset($noti_details['feature']['Interview']) && $noti_details['feature']['Interview'] == 1 ){
            $a_noti_count['interests'] = $interests++;
        }

        if( isset($noti_details['feature']['Interview']) && $noti_details['feature']['Interview'] == 1 ){
            $a_noti_count['interests'] = $interests++;
        }
    }
    ?>
      <!-- Item -->
      <div class="col-xl-3 col-sm-6">
         <div class="item d-flex align-items-center">
            <div class="icon bg-red"><i class="fas fa-thumbs-up"></i></div>
            <a href="#">
               <div class="title">
                  <span>Intresters</span>
                  <div class="progress">
                     <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="{#val.value}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-violet"></div>
                  </div>
               </div>
            </a>
            <div class="number"><strong><?php echo isset($a_noti_count['interests'])?$a_noti_count['interests']:0; ?></strong></div>
         </div>
      </div>
      <!-- Item -->
      <div class="col-xl-3 col-sm-6">
         <div class="item d-flex align-items-center">
            <div class="icon bg-green"><i class="fas fa-child"></i></div>
            <a href="#">
               <div class="title">
                  <span>Visitors</span>
                  <div class="progress">
                     <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="{#val.value}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                  </div>
               </div>
            </a>
            <div class="number"><strong><?php echo isset($a_noti_count['visits'])?$a_noti_count['visits']:0; ?></strong></div>
         </div>
      </div>
      <!-- Item -->
      <div class="col-xl-3 col-sm-6">
         <div class="item d-flex align-items-center">
            <div class="icon bg-info"><i class="fas fa-money-bill-alt"></i></div>
            <a href="#">
               <div class="title">
                  <span>My Subscriptions</span>
                  <div class="progress">
                     <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="{#val.value}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-green"></div>
                  </div>
            </a>
            </div>
         </div>
      </div>
      <!-- Item -->
      <div class="col-xl-3 col-sm-6">
         <div class="item d-flex align-items-center">
            <div class="icon bg-orange"><i class="fas fa-comments"></i></div>
            <a href="#">
               <div class="title">
                  <span>Chat</span>
                  <div class="progress">
                     <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="{#val.value}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-orange"></div>
                  </div>
               </div>
            </a>
            <div class="number"><strong>04</strong></div>
         </div>
      </div>
   </div>
</div>
<?php 
$a_high_noti = array();
$activities = '';
$latest = false;
foreach ($recent_notifications as $key => $value) {
    
    if( !$latest && ($value['trigger'] == INTERSTS_ID || $value['trigger'] == INTERVIEW_ID) ){
        $a_high_noti = $value;
        $latest = true;
    }

    if($value['trigger'] == INTERSTS_ID)
        $icon = site_url().'/assets/uploads/interest.png';
    else if($value['trigger'] == INTERVIEW_ID)
        $icon = site_url().'/assets/uploads/interview.png';
    else
        $icon = site_url().'/assets/uploads/visits.png';

    $activities .= '<div class="item" style="border-right:none;">
                     <div class="feed d-flex justify-content-between">
                        <div class="feed-body d-flex justify-content-between">
                           <a href="#" class="feed-profile"><img src="'.$icon.'" alt="person" class="img-fluid rounded-circle"></a>
                           <div class="content">
                              <h5>'.ucfirst($value['t_name']).'</h5>
                              <span>'.ucfirst($value['type']).' New '.implode(',', array_keys($value['feature'])).' '.$value['action'].' </span>
                              <div class="full-date"><small>'. date( 'l, F d', strtotime($value['notification_on'])).'</small></div>
                           </div>
                        </div>
                        <div class="date text-right"><small>'. date( 'h:i:s', strtotime($value['notification_on'])).'</small></div>
                     </div>
                  </div>';
}
?>
<!-- Projects Section-->
<section class="projects no-padding-top">
   <div class="container-fluid">
   <!-- Project-->
   <div class="project">
      <div class="row bg-white has-shadow">
         <div class="left-col col-lg-6 d-flex align-items-center justify-content-between">
            <div class="project-title d-flex align-items-center">
               <div class="image has-shadow">
                <?php
                if($value['trigger'] == INTERSTS_ID)
                    $icon = site_url().'/assets/uploads/interest.png';
                else
                    $icon = site_url().'/assets/uploads/interview.png';
                ?>
                <img src="<?php echo $icon; ?>" alt="..." class="img-fluid"></div>
                <div class="text">
                    <h3 class="h4"><?php echo ucfirst($a_high_noti['type']).' to '.ucfirst($a_high_noti['t_name']) ?> </h3>
                    <medium><?php echo ' New '.implode(',', array_keys($a_high_noti['feature'])).' '.$a_high_noti['action']; ?></medium>
                </div>
            </div>
            <div class="project-date"><span class="hidden-sm-down"><?php echo date( 'l, F d', strtotime($a_high_noti['notification_on'])); ?></span></div>
         </div>
         <div class="right-col col-lg-6 d-flex align-items-center">
            <div class="time"><i class="fa fa-clock-o"></i><?php echo date( 'h:i:s', strtotime($a_high_noti['notification_on'])); ?> </div>
            <div class="comments"><i class="fa fa-comment-o"></i>20</div>
            <div class="project-progress">
               <div class="progress">
                  <div role="progressbar" style="width: 45%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Project-->
<section class="updates no-padding-top">
   <div class="container-fluid">
      <div class="row bg-white has-shadow">
         <!-- Recent Updates-->
         <!-- Daily Feeds -->
         <div class="col-lg-4">
            <div class="daily-feeds card">
               <div class="card-header">
                  <h3 class="h4">Recent Activities</h3>
               </div>
               <div class="card-body no-padding">
                  <!-- Item-->
                  <?php echo $activities; ?>
               </div>
            </div>
         </div>
         <!-- Recent Activities -->
         <!--Carousel Wrapper-->
         <div class="col-md-8 mid-content-top profile-cnnt">
            <div class="middle-content">
               <h3 class="profile-title">Highlighted Profile</h3>
               <!-- start content_slider -->
               <div id="owl-demo" class="owl-carousel text-center">
                <?php //echo "<pre>";print_r($highligted_profiles); echo "</pre>"; ?>
                    <?php
                    foreach ($highligted_profiles as $key => $profiles) {
                        $name = $profiles['first_name'].' '.$profiles['middle_name'].' '.$profiles['last_name'];
                        $dp = (isset($profiles['dp']) && !empty($profiles['dp']))?$profiles['dp'][0]:site_url().'/assets/uploads/avatar.png';
                        
                        $a_tc = explode(',', $profiles['talent_category']);
                        $tc = '';

                        foreach ($a_tc as $tc_ic) {
                            $tc .= (isset($settings['talents_category'][$tc_ic]))?$settings['talents_category'][$tc_ic].', ':'';
                        }

                        echo '<div class="item">
                                 <img class="lazyOwl img-fluid" data-src="'.$dp.'" alt="name">
                                 <p class="img-names">'.$name.'<br>
                                    <span> '.$tc.'<span>
                                 </p>
                              </div>';
                    }
                    ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</setcion>