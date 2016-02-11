<?php
if (isset($view->userDetails)) {
    extract($view->userDetails);
}
if ($_POST) {
    extract($_POST);
}
?>
<div class="wrapper">
    <div class="container con-padding-tb">
        <!--profile screen-->
        <div class="col-xs-12 col-sm-12">
            <?php include($module_path . "/application/global/message.php"); ?>
            <div class="profile-sec-head profile-head-bg"> 
                <?php if ($view->userDetails['user_image'] != "") { ?>
                    <img src="<?php echo $module_url . '/images/user_images/' . $view->userDetails['user_image']; ?>" height="120px" width="120px" alt="" />
                <?php } else { ?>
                    <img src="http://placehold.it/103x103" alt="" />
                <?php } ?>
                <h1><i><?php echo $view->userDetails['first_name'];?></i> <?php echo $view->userDetails['last_name'];?></h1>
                <p class="text-center display-block"><?php echo $view->userDetails['email'];?></p>
            </div>
            <div class="all-emails">
                <ul id="scrollbox8">
                    <li>
                        <h5><a href="#" title="">Mobile Number</a></h5>
                        <p><?php echo $view->userDetails['mobile_number'];?></p>
                    </li>
                    <li>
                        <h5><a href="#" title="">Address</a></h5>
                        <p><?php echo $view->userDetails['address_line1'];?><br><?php echo $view->userDetails['address_line2'];?></p>                        
                    </li>
                    <li>
                        <h5><a href="#" title="">Birth Date</a></h5>
                        <p><?php echo $view->userDetails['birth_date'];?></p>
                    </li>
                    <li>
                        <h5><a href="#" title="">Currency</a></h5>
                        <p><?php echo (isset($view->userDetails['base_currency_id']) and !empty($view->userDetails['base_currency_id'])) ? $view->userDetails['currency_name']." [".$view->userDetails['currency_code']."]" : "";?></p>
                    </li>
                    <li>
                        <h5><a href="#" title="">City</a></h5>
                        <p><?php echo $view->userDetails['city'];?></p>
                    </li>
                    <li>
                        <h5><a href="#" title="">Post Code/Zip Code</a></h5>
                        <p><?php echo $view->userDetails['zip_code'];?></p>
                    </li>
                    <li>
                        <h5><a href="#" title="">Country</a></h5>
                        <p><?php echo $view->userDetails['name'];?></p>
                    </li>
                </ul>
            </div>
            <div class="col-xs-12 pro-btm-fix">
                <div class="col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-xs-6 col-md-12 pro-btns brd-right"><a href="<?php echo $module_url . "/users/change-security-question" ?>" title="">Change Security Question</a></div>
                    <div class="col-xs-6 col-md-12 pro-btns pro-btns brd-top change-pass-btn"><a href="<?php echo $module_url . "/users/change-password" ?>" title="">Change Password</a></div>
                </div>
            </div>
        </div>
    </div>
</div>