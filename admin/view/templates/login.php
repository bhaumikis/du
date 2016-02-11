<?php
if (isset($_COOKIE['cookie_admin_username'])) {
    $username = $_COOKIE['cookie_admin_username'];
    $password = $_COOKIE['cookie_admin_pass'];
    $remember_me = 1;
}
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Log in</title>

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/bootstrap.min.css" type="text/css" /><!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/font-awesome-4.0.3/css/font-awesome.css" type="text/css" /><!-- Font Awesome -->

    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/nv.d3.css" type="text/css" /><!-- VISITOR CHART -->
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPLICATION_URL; ?>/css/daterangepicker-bs3.css" /><!-- Date Range Picker -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/style.css" type="text/css" /><!-- Style -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/responsive.css" type="text/css" /><!-- Responsive -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/datepicker3.css" type="text/css" /><!-- date picker -->
    <noscript>Enable Javascript with your browser.</noscript>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/admin-style-extra.css">


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

</head>
<script type="text/javascript">
    $(document).ready(function() {

        $("#login").validate({
            rules: {
                email: {required: true},
                password: {required: true}
            },
            messages: {
                email: {required: "<?php echo _l('Enter_Email', 'users'); ?>"},
                password: {required: "<?php echo _l('Enter_Password', 'users'); ?>"}
            }
        });
    });
</script>
<body class="sign-in-bg">
    <header class="du-header">

    </header>
    <div class="sign-in">
<?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
        <div class="sign-in-head black">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="200px" height="58px" viewBox="0 0 200 58" enable-background="new 0 0 200 58" xml:space="preserve">
            <g>
            <g>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M1.352,15.093h10.833
                  c10,0,16.574,5.973,16.574,16.295c0,10.371-6.574,16.389-16.574,16.389H1.352V15.093z M11.861,43.796
                  c7.36,0,12.175-4.352,12.175-12.407c0-8.008-4.861-12.313-12.175-12.313H5.935v24.721H11.861z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.7842" stroke-miterlimit="10" d="M1.352,15.093h10.833
                  c10,0,16.574,5.973,16.574,16.295c0,10.371-6.574,16.389-16.574,16.389H1.352V15.093z M11.861,43.796
                  c7.36,0,12.175-4.352,12.175-12.407c0-8.008-4.861-12.313-12.175-12.313H5.935v24.721H11.861z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M44.945,33.148h1.019v-0.416
                  c0-3.843-2.176-5.14-5.139-5.14c-3.611,0-6.528,2.27-6.528,2.27l-1.852-3.288c0,0,3.379-2.778,8.75-2.778
                  c5.926,0,9.259,3.241,9.259,9.166v14.814h-4.167v-2.222c0-1.064,0.092-1.851,0.092-1.851h-0.092c0,0-1.898,4.628-7.5,4.628
                  c-4.027,0-7.962-2.453-7.962-7.129C30.825,33.473,41.01,33.148,44.945,33.148 M39.76,44.722c3.795,0,6.25-3.981,6.25-7.452v-0.742
                  h-1.157c-3.379,0-9.491,0.231-9.491,4.445C35.361,42.87,36.843,44.722,39.76,44.722"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.7842" stroke-miterlimit="10" d="M44.945,33.148h1.019v-0.416
                  c0-3.843-2.176-5.14-5.139-5.14c-3.611,0-6.528,2.27-6.528,2.27l-1.852-3.288c0,0,3.379-2.778,8.75-2.778
                  c5.926,0,9.259,3.241,9.259,9.166v14.814h-4.167v-2.222c0-1.064,0.092-1.851,0.092-1.851h-0.092c0,0-1.898,4.628-7.5,4.628
                  c-4.027,0-7.962-2.453-7.962-7.129C30.825,33.473,41.01,33.148,44.945,33.148z M39.76,44.722c3.795,0,6.25-3.981,6.25-7.452
                  v-0.742h-1.157c-3.379,0-9.491,0.231-9.491,4.445C35.361,42.87,36.843,44.722,39.76,44.722z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M55.141,15.093h4.537v4.584h-4.537V15.093
                  z M55.188,24.352h4.491v23.425h-4.491V24.352z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.7842" stroke-miterlimit="10" d="M55.141,15.093h4.537v4.584h-4.537V15.093
                  z M55.188,24.352h4.491v23.425h-4.491V24.352z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M64.425,15.093h4.49v25.508
                  c0,2.825,1.111,3.334,2.5,3.334c0.417,0,0.787-0.047,0.787-0.047v3.982c0,0-0.694,0.092-1.481,0.092
                  c-2.546,0-6.296-0.693-6.296-6.573V15.093z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.7842" stroke-miterlimit="10" d="M64.425,15.093h4.49v25.508
                  c0,2.825,1.111,3.334,2.5,3.334c0.417,0,0.787-0.047,0.787-0.047v3.982c0,0-0.694,0.092-1.481,0.092
                  c-2.546,0-6.296-0.693-6.296-6.573V15.093z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M73.351,52.639
                  c0,0,1.296,0.974,2.731,0.974c1.807,0,3.334-1.297,4.213-3.427l1.158-2.686l-9.815-23.147h5.046l5.879,15.323
                  c0.463,1.204,0.879,2.825,0.879,2.825h0.093c0,0,0.371-1.574,0.788-2.778l5.694-15.37h4.861L83.907,51.991
                  c-1.436,3.61-4.306,5.509-7.593,5.509c-2.64,0-4.538-1.436-4.538-1.436L73.351,52.639z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.7842" stroke-miterlimit="10" d="M73.351,52.639
                  c0,0,1.296,0.974,2.731,0.974c1.807,0,3.334-1.297,4.213-3.427l1.158-2.686l-9.815-23.147h5.046l5.879,15.323
                  c0.463,1.204,0.879,2.825,0.879,2.825h0.093c0,0,0.371-1.574,0.788-2.778l5.694-15.37h4.861L83.907,51.991
                  c-1.436,3.61-4.306,5.509-7.593,5.509c-2.64,0-4.538-1.436-4.538-1.436L73.351,52.639z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M98.153,15.093h4.583v21.111
                  c0,5,3.24,7.917,8.24,7.917c5.046,0,8.334-2.917,8.334-8.01V15.093h4.583v21.111c0,7.269-5.231,12.129-12.871,12.129
                  c-7.638,0-12.87-4.86-12.87-12.129V15.093z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.7842" stroke-miterlimit="10" d="M98.153,15.093h4.583v21.111
                  c0,5,3.24,7.917,8.24,7.917c5.046,0,8.334-2.917,8.334-8.01V15.093h4.583v21.111c0,7.269-5.231,12.129-12.871,12.129
                  c-7.638,0-12.87-4.86-12.87-12.129V15.093z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M129.376,41.713
                  c0,0,2.64,2.731,6.712,2.731c1.945,0,3.89-1.018,3.89-2.916c0-4.305-12.037-3.427-12.037-11.065c0-4.259,3.797-6.666,8.473-6.666
                  c5.138,0,7.545,2.592,7.545,2.592l-1.806,3.38c0,0-2.082-2.083-5.785-2.083c-1.945,0-3.844,0.833-3.844,2.87
                  c0,4.214,12.037,3.287,12.037,10.973c0,3.889-3.333,6.806-8.473,6.806c-5.74,0-8.888-3.427-8.888-3.427L129.376,41.713z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.7842" stroke-miterlimit="10" d="M129.376,41.713
                  c0,0,2.64,2.731,6.712,2.731c1.945,0,3.89-1.018,3.89-2.916c0-4.305-12.037-3.427-12.037-11.065c0-4.259,3.797-6.666,8.473-6.666
                  c5.138,0,7.545,2.592,7.545,2.592l-1.806,3.38c0,0-2.082-2.083-5.785-2.083c-1.945,0-3.844,0.833-3.844,2.87
                  c0,4.214,12.037,3.287,12.037,10.973c0,3.889-3.333,6.806-8.473,6.806c-5.74,0-8.888-3.427-8.888-3.427L129.376,41.713z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M158.31,23.797
                  c6.621,0,10.139,4.907,10.139,10.973c0,0.602-0.139,1.944-0.139,1.944h-17.084c0.233,5.139,3.889,7.73,8.056,7.73
                  c4.028,0,6.945-2.731,6.945-2.731l1.852,3.286c0,0-3.474,3.334-9.073,3.334c-7.361,0-12.408-5.324-12.408-12.268
                  C146.597,28.612,151.644,23.797,158.31,23.797 M163.865,33.334c-0.139-4.029-2.64-5.973-5.647-5.973
                  c-3.426,0-6.204,2.129-6.853,5.973H163.865z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="1.7842" stroke-miterlimit="10" d="M158.31,23.797
                  c6.621,0,10.139,4.907,10.139,10.973c0,0.602-0.139,1.944-0.139,1.944h-17.084c0.233,5.139,3.889,7.73,8.056,7.73
                  c4.028,0,6.945-2.731,6.945-2.731l1.852,3.286c0,0-3.474,3.334-9.073,3.334c-7.361,0-12.408-5.324-12.408-12.268
                  C146.597,28.612,151.644,23.797,158.31,23.797z M163.865,33.334c-0.139-4.029-2.64-5.973-5.647-5.973
                  c-3.426,0-6.204,2.129-6.853,5.973H163.865z"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M198.647,10.179
                  c0-5.344-4.591-9.679-10.256-9.679c-3.414,0-6.438,1.576-8.302,3.996c1.689-1.159,3.772-1.845,6.021-1.845
                  c5.666,0,10.257,4.335,10.257,9.681c0,2.125-0.725,4.087-1.954,5.682C196.979,16.255,198.647,13.402,198.647,10.179"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M194.754,17.523
                  c0-8.568-6.034-15.514-16.439-15.514c-5.472,0-10.315,2.525-13.304,6.406c2.711-1.858,6.045-2.959,9.651-2.959
                  c9.08,0,16.439,6.947,16.439,15.515c0,3.402-1.165,6.55-3.134,9.107C192.079,27.258,194.754,22.686,194.754,17.523"/>
            <path fill="#FFFFFF" stroke="#FFFFFF" stroke-width="0.8872" stroke-miterlimit="10" d="M187.635,29.321
                  c0-12.864-11.051-23.292-24.684-23.292c-8.214,0-15.486,3.792-19.974,9.618c4.069-2.791,9.073-4.441,14.49-4.441
                  c13.632,0,24.682,10.429,24.682,23.292c0,5.11-1.749,9.835-4.707,13.676C183.615,43.939,187.635,37.075,187.635,29.321"/>
            </g>
            <g>
            <path fill="#FFFFFF" d="M170.168,24.174v3.068h-0.692v-3.068h-0.935v-0.662h2.562v0.662H170.168z"/>
            <path fill="#FFFFFF" d="M173.608,26.77h-0.497l-0.835-1.91l0.025,2.382h-0.677v-3.73h0.755l0.979,2.283l0.981-2.283h0.755v3.73
                  h-0.676l0.023-2.397L173.608,26.77z"/>
            </g>
            </g>
            </svg>
        </div>
        <div class="sign-in-form">

            <form name="login" id="login" method="post">
                <i class="fa fa-envelope"></i><input type="text" name="email" placeholder="Email Address" value="<?php echo $username; ?>" />
                <div>&nbsp;</div>
                <i class="fa fa-lock"></i><input type="password" name="password" placeholder="Password" value="<?php echo $password; ?>" />
                <div>&nbsp;</div>
                <div class="rememberme">
                    <section class="customCheckBoxWrapper right-margin">
                        <div class="customCheckBox">
                            <input <?php echo ($remember_me == 1) ? "checked" : ""; ?> type="checkbox" id="admin_remember" name="admin_remember"><label for="admin_remember"></label>
                        </div>
                    </section>
                    <label for="admin_remember"> Remember me</label>
                    <a class="forget-text" href="<?php echo $module_url . "/forgotpassword"; ?>" title="">Forgot your password?</a>

                </div>
                <input type="submit" name="submit" value="LOG IN" class="login-btn black">
                <!--<span class="newuser">New User? <a href="<?php echo $module_url . "/register"; ?>">Register Here</a></span>-->
            </form>
        </div>
    </div>
</body>
</html>