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
                        <a class="navbar-brand" href="index.html">
                            <div class="brand-text brand-big">
                                Celebrity Be
                            </div>
                            <div class="brand-text brand-small">
                                <strong>
                                    CB
                                </strong>
                            </div>
                        </a>
                        <!-- Toggle Button-->
                        <a class="menu-btn active" href="#" id="toggle-btn">
                            <span>
                            </span>
                            <span>
                            </span>
                            <span>
                            </span>
                        </a>
                    </div>
                    <!-- Navbar Menu -->
                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                        <!-- Notifications-->
                        <li class="nav-item dropdown">
                            <a aria-expanded="false" aria-haspopup="true" class="nav-link" data-target="#" data-toggle="dropdown" href="#" id="notifications" rel="nofollow">
                                <i class="fas fa-bell">
                                </i>
                                <span class="badge bg-red">
                                    12
                                </span>
                            </a>
                            <ul aria-labelledby="notifications" class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" rel="nofollow">
                                        <div class="notification">
                                            <div class="notification-content">
                                                <i class="fa fa-envelope bg-green">
                                                </i>
                                                You have 6 new messages
                                            </div>
                                            <div class="notification-time">
                                                <small>
                                                    4 minutes ago
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" rel="nofollow">
                                        <div class="notification">
                                            <div class="notification-content">
                                                <i class="fa fa-twitter bg-blue">
                                                </i>
                                                You have 2 followers
                                            </div>
                                            <div class="notification-time">
                                                <small>
                                                    4 minutes ago
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" rel="nofollow">
                                        <div class="notification">
                                            <div class="notification-content">
                                                <i class="fa fa-upload bg-orange">
                                                </i>
                                                Server Rebooted
                                            </div>
                                            <div class="notification-time">
                                                <small>
                                                    4 minutes ago
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" rel="nofollow">
                                        <div class="notification">
                                            <div class="notification-content">
                                                <i class="fa fa-twitter bg-blue">
                                                </i>
                                                You have 2 followers
                                            </div>
                                            <div class="notification-time">
                                                <small>
                                                    10 minutes ago
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item all-notifications text-center" href="#" rel="nofollow">
                                        <strong>
                                            view all notifications
                                        </strong>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Logout    -->
                        <li class="nav-item">
                            <a class="nav-link logout" href="login_new.html">
                                Logout
                                <i class="fas fa-arrow-left">
                                </i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="page-content d-flex align-items-stretch">
        <!-- Side Navbar -->
        <?php require_once 'templates/users-sidebar.php'; ?>

        <div class="content-inner">
            <!-- Page Header-->
            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">
                        Profile Intresters and Visitors
                    </h2>
                </div>
            </header>
            <?php
            $visits_html = '';
            $interests_html = '';

            foreach ($notifications as $key => $noti_details) {

                if( isset($noti_details['feature']['Visits']) ){
                    if(isset($noti_details['dp']) && $noti_details['dp'] != ''){
                        $dp = $noti_details['dp'];
                    } else {
                        $dp = asset_url('uploads/').'avatar.png';
                    }
                    // $a_dashboard['visits'] = $noti_details;
                    $visits_html .= '<div class="item d-flex align-items-center">
                                            <div class="image">
                                                <img alt="Profile" class="img-fluid rounded-circle" src="'.$dp.'"/>
                                            </div>
                                            <div class="text">
                                                <a href="#">
                                                    <h3 class="h5">
                                                        '.$noti_details['name'].'
                                                    </h3>
                                                </a>
                                                <small>
                                                    Posted on '.$noti_details['notification_on'].'
                                                </small>
                                            </div>
                                        </div>';
                }

                if( isset($noti_details['feature']['Interview']) ){

                    $a_dashboard['interests'] = $noti_details;
                    $interests_html .= '<div class="item d-flex align-items-center visitor-section new-bg">
                                            <div class="image">
                                                <img alt="Profile" class="img-fluid rounded-circle" src="'.$dp.'"/>
                                            </div>
                                            <div class="text">
                                                <a href="#">
                                                    <h3 class="h5">
                                                        '.$noti_details['name'].'
                                                    </h3>
                                                </a>
                                                <small>
                                                    Posted on '.$noti_details['notification_on'].'
                                                </small>
                                            </div>
                                        </div>';
                }
            }
            ?>
            <!-- Dashboard Counts Section-->
            <section class="dashboard-counts">
                <div class="container-fluid">
                    <div class="row bg-white has-shadow">
                        <div class="col-lg-6 intrest-section">
                            <div class="articles card">
                                <div class="card-header d-flex align-items-center">
                                    <h2 class="h3">
                                        Profile Intresters
                                    </h2>
                                    <div class="badge badge-rounded bg-green">
                                        4 New
                                    </div>
                                </div>
                                <div class="card-body no-padding">
                                    <?php
                                    echo $interests_html;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 visitors-section">
                            <div class="articles card new-bg">
                                <div class="card-header d-flex align-items-center new-bg">
                                    <h2 class="h3">
                                        Profile Visitors
                                    </h2>
                                    <div class="badge badge-rounded bg-info">
                                        4 New
                                    </div>
                                </div>
                                <div class="card-body no-padding new-bg">
                                    <?php
                                    echo $visits_html;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            