<?php
if (isset($_COOKIE['cookie_du_username'])) {
    $username = $_COOKIE['cookie_du_username'];
    $password = $_COOKIE['cookie_du_pass'];
    $remember_me = 1;
}
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log in</title>

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/bootstrap.min.css" type="text/css" /><!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/font-awesome-4.0.3/css/font-awesome.css" type="text/css" /><!-- Font Awesome -->

    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/nv.d3.css" type="text/css" /><!-- VISITOR CHART -->
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPLICATION_URL; ?>/css/daterangepicker-bs3.css" /><!-- Date Range Picker -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/style.css" type="text/css" /><!-- Style -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/responsive.css" type="text/css" /><!-- Responsive -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/datepicker3.css" type="text/css" /><!-- date picker -->

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/style-extra.css">
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>


    <!-- Script -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-1.10.2.js"></script><!-- Jquery -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/d3.v2.js"></script><!-- VISITOR CHART -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/nv.d3.js"></script><!-- VISITOR CHART -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/live-updating-chart.js"></script><!-- VISITOR CHART -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/bootstrap.min.js"></script><!-- Bootstrap -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/script.js"></script><!-- Script -->
<!--    <script src="<?php echo APPLICATION_URL; ?>/js/jquery.easypiechart.min.js"></script>  Easy Pie Chart -->
<!--    <script src="<?php echo APPLICATION_URL; ?>/js/easy-pie-chart.js"></script>  Easy Pie Chart -->
    <script src="<?php echo APPLICATION_URL; ?>/js/skycons.js"></script> <!-- Skycons -->
    <script src="<?php echo APPLICATION_URL; ?>/js/enscroll-0.5.2.min.js"></script> <!-- Custom Scroll bar -->
    <script src="<?php echo APPLICATION_URL; ?>/js/moment.js"></script> <!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/daterangepicker.js"></script><!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/ticker.js"></script><!-- Ticker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/html5lightbox.js"></script><!-- Ticker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery.validate.js"></script><!-- Validation -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-validate.bootstrap-tooltip.js"></script><!-- Validation -->
    <noscript>Enable Javascript with your browser.</noscript>
</head>
<script type="text/javascript">
    $(document).ready(function() {
    	$("#client_date").val(moment().toLocaleString());
    	$("#client_timezone").val(moment().format("Z"));
        $("#login").validate({
            rules: {
                mobile_number: {required: true},
                password: {required: true}
            },
            messages: {
                mobile_number: {required: "<?php echo _l('Enter_Mobile', 'users'); ?>"},
                password: {required: "<?php echo _l('Enter_Password', 'users'); ?>"}
            }
        });
    });
</script>
<body class="sign-in-bg">
    <header class="du-header">

    </header>
    <div class="sign-in">
        <?php include($module_path . "/application/global/message.php"); ?>
        <div class="sign-in-head black">
            <div class="logo"></div>

        </div>
        <div class="sign-in-form">

            <form name="login" id="login" method="post">
                <div class="sigin-div">
                    <i class="fa fa-phone"></i><input type="text" name="mobile_number" placeholder="<?php echo _l('Placeholder_Login_Mobile', 'users'); ?>" value="<?php echo $username; ?>" />
                </div>

                <div class="sigin-div">
                    <i class="fa fa-lock"></i><input type="password" name="password" placeholder="<?php echo _l('Placeholder_Login_Password', 'users'); ?>" value="<?php echo $password; ?>" />
                </div>

                <div class="rememberme">
                    <section class="customCheckBoxWrapper right-margin">
                        <div class="customCheckBox">
                            <input <?php echo ($remember_me == 1) ? "checked" : ""; ?> type="checkbox" id="remember" name="remember" value="1"><label for="remember"></label>
                        </div>
                    </section>
                    <label for="remember"> <?php echo _l('Login_Remember_Me', 'users'); ?></label>
                    <a class="forget-text" href="<?php echo $module_url . "/forgotpassword"; ?>" title=""><?php echo _l('Login_Forgot_Password', 'users'); ?></a>
                </div>
                <input type="hidden" id="client_date" name="client_date" value="" />
                <input type="hidden" id="client_timezone" name="client_timezone" value="" />
                <input type="hidden" id="is_locale_set" name="is_locale_set" value="1" />
                
                
                <input type="submit" name="submit" value="<?php echo _l('Login_Value', 'users'); ?>" class="login-btn black">
                <span class="newuser"><?php echo _l('Login_New_User', 'users'); ?> <a href="<?php echo $module_url . "/register"; ?>"><?php echo _l('Login_Register', 'users'); ?></a></span>
            </form>
        </div>
    </div>
</body>
</html>