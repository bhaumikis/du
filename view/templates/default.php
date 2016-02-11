<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title><?php echo _l("Daily Use", "common"); ?></title>

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/bootstrap.min.css" type="text/css" /><!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/font-awesome-4.0.3/css/font-awesome.css" type="text/css" /><!-- Font Awesome -->

    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPLICATION_URL; ?>/css/daterangepicker-bs3.css" /><!-- Date Range Picker -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/style.css" type="text/css" /><!-- Style -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/responsive.css" type="text/css" /><!-- Responsive -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/datepicker3.css" type="text/css" /><!-- date picker -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/msdropdown/dd.css" type="text/css" /><!-- flag country -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/msdropdown/flags.css" type="text/css" /><!-- flag country -->
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/style-extra.css">



    <!-- Script -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-1.10.2.js"></script><!-- Jquery -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/bootstrap.min.js"></script><!-- Bootstrap -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/script.js"></script><!-- Script -->
    <script src="<?php echo APPLICATION_URL; ?>/js/skycons.js"></script> <!-- Skycons -->
    <script src="<?php echo APPLICATION_URL; ?>/js/enscroll-0.5.2.min.js"></script> <!-- Custom Scroll bar -->
    <script src="<?php echo APPLICATION_URL; ?>/js/moment.js"></script> <!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/daterangepicker.js"></script><!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery.validate.js"></script><!-- Validation -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-validate.bootstrap-tooltip.js"></script><!-- Validation -->
    <script src="<?php echo APPLICATION_URL; ?>/js/bootstrap-datepicker.js"></script><!-- Ticker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/common.js"></script>
    <script src="<?php echo APPLICATION_URL; ?>/js/msdropdown/jquery.dd.min.js"></script><!-- flag country -->
    <script src="<?php echo APPLICATION_URL; ?>/js/bootstrap.file-input.js"></script><!-- File Upload -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/locale.js"></script><!-- Script -->
    <?php echo $view->renderExtraJS(); ?>
    <?php echo $view->renderExtraCSS(); ?>
    <noscript>Your browser does not support JavaScript!</noscript>
</head>
<body <?php echo ($option . "#" . $action == 'dashboard#index') ? 'class="dashboard-bg"' : ""; ?>>

    <!-- Start: Header -->
    <header class="">
        <?php include($module_path . "/view/header/header.php"); ?>
    </header>
    <!--    <header class="du-header">
    <?php include($module_path . "/view/header/header_html.php"); ?>
        </header>-->
    <!-- End: Header -->

    <!-- Left Menu -->
    <?php if ($option . "#" . $action != 'dashboard#index') { ?>
        <div class="menu">
            <?php include($module_path . "/view/left/left.php"); ?>
        </div>
    <?php } ?>
    <!-- Left Menu -->

    <?php include($middle); ?>

    <?php include($module_path . "/view/middle/inline-edit/popup.php"); ?>
    <!-- RAIn ANIMATED ICON-->
    <script>
        var icons = new Skycons();
        icons.set("rain", Skycons.RAIN);
        icons.play();
    </script>
</body>
</html>
