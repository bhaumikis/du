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

    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/style.css" type="text/css" /><!-- Style -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/responsive.css" type="text/css" /><!-- Responsive -->

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/style-extra.css">



    <!-- Script -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-1.10.2.js"></script><!-- Jquery -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/bootstrap.min.js"></script><!-- Bootstrap -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/script.js"></script><!-- Script -->
    <script src="<?php echo APPLICATION_URL; ?>/js/skycons.js"></script> <!-- Skycons -->
    <script src="<?php echo APPLICATION_URL; ?>/js/enscroll-0.5.2.min.js"></script> <!-- Custom Scroll bar -->
    <script src="<?php echo APPLICATION_URL; ?>/js/moment.js"></script> <!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery.validate.js"></script><!-- Validation -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-validate.bootstrap-tooltip.js"></script><!-- Validation -->
    <?php echo $view->renderExtraJS(); ?>
    <?php echo $view->renderExtraCSS(); ?>
    <noscript>Enable Javascript with your browser.</noscript>
</head>
<body>

    <!-- Start: Header -->
    <header class="">
        <?php #include($module_path . "/view/header/header.php"); ?>
    </header>
    <!-- End: Header -->
    <?php include($middle); ?>
</body>
</html>
