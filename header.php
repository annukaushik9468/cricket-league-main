<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Cricket League</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<script>
  if ( window.history.replaceState )
  {
   window.history.replaceState( null, null, window.location.href );
  }
</script>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">NiceAdmin</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">K. Anderson</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Kevin Anderson</h6>
              <span>Web Designer</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

<li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Player</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tbl_player.php">
              <i class="bi bi-circle"></i><span>Add Player</span>
            </a>
          </li>
          <li>
            <a href="tbl_playerlisting.php">
              <i class="bi bi-circle"></i><span>Player Listing</span>
            </a>
          </li>
           </ul>
      </li><!-- End Components Nav -->

        <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Country & State</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">  
          <li>
            <a href="tbl_country.php">
              <i class="bi bi-circle"></i><span>Add Country</span>
            </a>
          </li>
          <li>
            <a href="tbl_countrylisting.php">
              <i class="bi bi-circle"></i><span>Country Listing</span>
            </a>
          </li>
          <li>
            <a href="tbl_state.php">
              <i class="bi bi-circle"></i><span>Add State</span>
            </a>
          </li>
          <li>
            <a href="tbl_statelisting.php">
              <i class="bi bi-circle"></i><span>State Listing</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->

       <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Season & Series</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tbl_season.php">
              <i class="bi bi-circle"></i><span>Add Season</span>
            </a>
          </li>
          <li>
            <a href="tbl_seasonlisting.php">
              <i class="bi bi-circle"></i><span>Season Listing</span>
            </a>
          </li>
           <li>
            <a href="tbl_series.php">
              <i class="bi bi-circle"></i><span>Add Series</span>
            </a>
          </li>
          <li>
            <a href="tbl_serieslisting.php">
              <i class="bi bi-circle"></i><span>Series Listing</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Team</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tbl_team.php">
              <i class="bi bi-circle"></i><span>Add Team</span>
            </a>
          </li>
          <li>
            <a href="tbl_teamlisting.php">
              <i class="bi bi-circle"></i><span>Team Listing</span>
            </a>
          </li>
        </ul>
      </li><!-- End Charts Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gem"></i><span>Schedule</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tbl_schedule.php">
              <i class="bi bi-circle"></i><span>Add Schedule</span>
            </a>
          </li>
          <li>
            <a href="tbl_schedulelisting.php">
              <i class="bi bi-circle"></i><span>Schedule Listing</span>
            </a>
          </li>
        </ul>
      </li><!-- End Icons Nav -->

 <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icon-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gem"></i><span>Player Data With Team</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icon-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tbl_teamselection.php">
              <i class="bi bi-circle"></i><span>Add Player Team</span>
            </a>
          </li>
          <li>
            <a href="tbl_teamselection_listing.php">
              <i class="bi bi-circle"></i><span>Player Team Listing</span>
            </a>
          </li>
        </ul>
      </li><!-- End Icons Nav -->

 <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#chart-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Player Data With CSV</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="chart-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tbl_teamplayer.php">
              <i class="bi bi-circle"></i><span>Player Entry With CSV Data</span>
            </a>
          </li>
          <li>
            <a href="tbl_teamplayer_listing.php">
              <i class="bi bi-circle"></i><span>Player CSV Data Listing</span>
            </a>
          </li>
        </ul>
      </li><!-- End Charts Nav -->

        
    

  <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_team_record.php">
          <i class="bi bi-grid"></i>
          <span>Upload CSV file</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_player_record.php">
          <i class="bi bi-grid"></i>
          <span>Add Player Total Single Match CSV Data</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_players.php">
           <i class="bi bi-grid"></i>
          <span>Player Single Search And Upload CSV</span>
        </a>
      </li><!-- End Profile Page Nav -->

       <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_battsman.php">
           <i class="bi bi-grid"></i>
          <span>Battsman VS Bowler Total Run Data Fetch</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_battsman_record.php">
           <i class="bi bi-grid"></i>
          <span>Battsman Total Run Data Fetch</span>
        </a>
      </li><!-- End Profile Page Nav -->

<li class="nav-item">
        <a class="nav-link collapsed" href="tbl_match_result.php">
           <i class="bi bi-grid"></i>
          <span>Match Toss & Win Result</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_toss_filter_data.php">
           <i class="bi bi-grid"></i>
          <span>Player With Match No & Toss Data</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_filter_data.php">
           <i class="bi bi-grid"></i>
          <span>Filter Player Batting & Fielding Record</span>
        </a>
      </li><!-- End Profile Page Nav -->

       <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_fielding_batting.php">
           <i class="bi bi-grid"></i>
          <span>Filter Player Both Batting & Fielding Record</span>
        </a>
      </li><!-- End Profile Page Nav -->

       <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_run-result.php">
           <i class="bi bi-grid"></i>
          <span>Total Run Of Player</span>
        </a>
      </li><!-- End Profile Page Nav -->

       <li class="nav-item">
        <a class="nav-link collapsed" href="tbl_run_series.php">
           <i class="bi bi-grid"></i>
          <span>Player Total Run With Series</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-login.html">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li><!-- End Login Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
     
    </div><!-- End Page Title -->



<script>
  if ( window.history.replaceState )
  {
   window.history.replaceState( null, null, window.location.href );
  }
</script>