<div class="menu-profile" id="intro3">
    <?php if ($_SESSION[$session_prefix]['user']['user_image'] != "") { ?>
        <img src="<?php echo APPLICATION_URL . '/images/user_images/' . $_SESSION[$session_prefix]['user']['user_image']; ?>" height="120px" width="120px" alt="" />
    <?php } else { ?>
        <img src="http://placehold.it/57x57" alt="" />
    <?php } ?>
    <h1 class="clearfix"><i><?php echo ($_SESSION[$session_prefix]['user']['first_name'] != "") ? $_SESSION[$session_prefix]['user']['first_name'] : "Welcome"; ?></i> <?php echo ($_SESSION[$session_prefix]['user']['last_name'] != "") ? $_SESSION[$session_prefix]['user']['last_name'] : "Guest"; ?></h1>
</div>
<ul>
    <li><a href="<?php echo $module_url . "/dashboard" ?>" title="" ><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <?php if ($obj->checkLoggedInAsSuperAdmin()) { ?>
    <li><a href="#" title="" ><i class="fa fa-users"></i>Users</a>
        <ul>
            <li><a href="<?php echo $module_url . "/users/admin-users" ?>" title="">Admin Users</a></li>
            <li><a href="<?php echo $module_url . "/users/end-users" ?>" title="">End Users</a></li>
        </ul>
    </li>
    <?php } else { ?>
        <li><a href="<?php echo $module_url . "/users/end-users" ?>" title="Users" ><i class="fa fa-users"></i>Users</a></li>
    <?php } ?>
    <li><a href="#" title="" ><i class="fa fa-ticket"></i>Tickets</a>
        <ul>
            <?php if ($obj->checkLoggedInAsSuperAdmin()) { ?>
                <li><a href="<?php echo $module_url . "/tickets" ?>" title="" >List</a></li>
                <li><a href="<?php echo $module_url . "/ticket-reports" ?>" title="">Reports</a></li>
            <?php } else { ?>
                <li><a href="<?php echo $module_url . "/tickets" ?>" title="" >List</a></li>
                <li><a href="<?php echo $module_url . "/ticket-reports/view-ticket-assignments" ?>" title="">Reports</a></li>
            <?php } ?>
        </ul>
    </li>
    <?php if ($obj->checkLoggedInAsSuperAdmin() || $_SESSION[$session_prefix]['user']['assign_tickets'] == "1") { ?>
    <li><a href="<?php echo $module_url . "/ticket-assignments" ?>" title="" ><i class="fa fa-ticket"></i>Assign Ticket</a></li>
    <?php } ?>
    <li><a href="<?php echo $module_url . "/security-questions" ?>" title="" ><i class="fa fa-question"></i>Security Questions</a></li>
    <li><a href="<?php echo $module_url . "/emailtemplates" ?>" title="" ><i class="fa fa-mail-forward"></i>Email Templates</a>
        <ul>
            <li><a href="<?php echo $module_url . "/emailtemplates/default" ?>" title="" >Default Email Template</a></li>
            <li><a href="<?php echo $module_url . "/emailtemplates/promotional" ?>" title="" >Promotional Email Template</a></li>
        </ul>
    </li>    
    <li><a href="#" title="" ><i class="fa fa-user"></i>Profile</a>
        <ul>
            <li><a href="<?php echo $module_url . "/users/manage-my-account" ?>" title="" >View/Edit</a></li>
            <li><a href="<?php echo $module_url . "/users/change-password" ?>" title="">Change Password</a></li>
            <li><a href="<?php echo $module_url . "/users/change-security-question" ?>" title="">Change Security Question</a></li>
        </ul>
    </li>
    <li><a href="<?php echo $module_url . "/index/logout" ?>" title="" ><i class="fa fa-sign-out"></i>Logout</a></li>
</ul>
