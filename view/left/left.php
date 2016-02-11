  <div class="menu-profile" id="intro3"> 
          <?php 
          $userName = $view->helper('user')->getUserName($_SESSION[$session_prefix]['user']['user_id']);          
          $userImage = $view->helper('user')->getUserImage($_SESSION[$session_prefix]['user']['user_id']);          
          if(file_exists(APPLICATION_PATH . '/images/user_images/' .$userImage['user_image']) and !empty($userImage['user_image'])) { ?>
        <img src="<?php echo APPLICATION_URL . '/images/user_images/' . $userImage['user_image']; ?>" height="120px" width="120px" alt="" />
    <?php } else { ?>
        <img src="<?php echo APPLICATION_URL . '/images/sign-in.jpg'; ?>" alt="" />
    <?php } ?>
<h1 class="clearfix"><i><?php echo (isset($userName['first_name']) and !empty($userName['first_name'])) ? $userName['first_name'] : _l("Welcome","left-menu") ?> <?php echo (isset($userName['last_name']) and !empty($userName['last_name'])) ? $userName['last_name'] : _l("Guest","left-menu"); ?></i></h1>        
  </div>
  <ul>
    <li <?php echo ($strPageTitle == "dashboard") ? "class='active'" : ""; ?>>
        <a href="<?php echo APPLICATION_URL;?>/dashboard" title="Dashboard" ><i class="fa fa-dashboard"></i><?php echo _l("DASHBOARD","left-menu");?></a>
<!--      <ul>
        <li><a href="dashboard.html" title="">Dashboard 1</a></li>
        <li><a href="dashboard2.html" title="">Dashboard 2</a></li>
        <li><a href="dashboard3.html" title="">Dashboard 3</a></li>
        <li><a href="dashboard4.html" title="">Dashboard 4</a></li>
        <li><a href="dashboard5.html" title="">Wide Dashboard</a></li>
      </ul>-->
    </li>
<!--    <li><a href="widget.html" title="" ><i class="fa fa-dollar"></i>Pay</a></li>-->
    <li <?php echo ($strPageTitle == "my-expenses") ? "class='active'" : ""; ?>><a href="<?php echo APPLICATION_URL;?>/my-expenses" title="My Expenses" ><i class="fa fa-money"></i><?php echo _l("MY_EXPENSES","left-menu");?></a></li>
    <li <?php echo ($strPageTitle == "my-categories") ? "class='active'" : ""; ?>><a href="<?php echo APPLICATION_URL;?>/my-categories" title="My Expense Categories" ><i class="fa fa-bars"></i><?php echo _l("MY_CATEGORIES","left-menu");?></a></li>
    <li <?php echo ($strPageTitle == "my-travel-plan") ? "class='active'" : ""; ?>><a href="<?php echo APPLICATION_URL;?>/my-travel-plan" title="My Travel Plan" ><i class="fa fa-plane"></i><?php echo _l("MY_TRAVEL_PLAN","left-menu");?></a></li>
    <!--<li><a href="#" title="" ><i class="fa fa-rocket"></i>My Vendors</a></li>-->
    <!--<li <?php echo ($strPageTitle == "my-cards") ? "class='active'" : ""; ?>><a href="#" title="My Cards" ><i class="fa fa-credit-card"></i>My Cards</a> </li>-->
    <li <?php echo ($strPageTitle == "my-vendors") ? "class='active'" : ""; ?>><a href="<?php echo APPLICATION_URL;?>/my-vendors" title="My Vendors" ><i class="fa fa-user"></i><?php echo _l("MY_VENDOR","left-menu");?></a> </li>
<!--    <li><a href="#" title="" ><i class="fa fa-thumbs-o-up"></i>My Cards</a></li>-->
    <li <?php echo ($strPageTitle == "app-settings") ? "class='active'" : ""; ?>>
        <a href="<?php echo APPLICATION_URL;?>/users/app-settings" title="App Settings" ><i class="fa fa-gear"></i><?php echo _l("APP_SETTINGS","left-menu");?></a></li>
<!--    <li><a href="#" title="" ><i class="fa fa-thumbs-o-up"></i>My Categories</a></li>
    <li><a href="#" title="" ><i class="fa fa-thumbs-o-up"></i>My Vendors</a></li>-->
<!--    <li><a href="#" title="" ><i class="fa fa-question"></i>Help</a></li>-->
    <li <?php echo ($strPageTitle == "my-profile" || $strPageTitle == "check-credentials" || $strPageTitle == "change-password" || $strPageTitle == "change-security-question") ? "class='active'" : ""; ?>><a href="<?php echo $module_url . "/users/my-profile" ?>" title="Profile" ><i class="fa fa-user"></i><?php echo _l("MY_PROFILE","left-menu");?></a></li>
    <li><a href="<?php echo $module_url . "/index/logout" ?>" title="Logout" ><i class="fa fa-sign-out"></i><?php echo _l("LOGOUT","left-menu");?></a></li>
  </ul>
