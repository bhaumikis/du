<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>

    <!-- Script -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/script.js"></script><!-- Script -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/jquery-1.10.2.js"></script><!-- Script -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/bootstrap.min.js"></script><!-- Bootstrap -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery.validate.js"></script><!-- Validation -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-validate.bootstrap-tooltip.js"></script><!-- Validation -->
    <script src="<?php echo APPLICATION_URL; ?>/js/moment.js"></script> <!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/daterangepicker.js"></script><!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/ticker.js"></script><!-- Ticker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/html5lightbox.js"></script><!-- Ticker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/bootstrap-datepicker.js"></script><!-- Ticker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/msdropdown/jquery.dd.min.js"></script><!-- flag country -->

    <link type="text/css" rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/bootstrap.min.css" type="text/css" /><!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/font-awesome-4.0.3/css/font-awesome.css" type="text/css" /><!-- Font Awesome -->

    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/nv.d3.css" type="text/css" /><!-- VISITOR CHART -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/style.css" type="text/css" /><!-- Style -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/responsive.css" type="text/css" /><!-- Responsive -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/datepicker3.css" type="text/css" /><!-- date picker -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/msdropdown/dd.css" type="text/css" /><!-- flag country -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/msdropdown/flags.css" type="text/css" /><!-- flag country -->
    <?php echo $view->renderExtraJS(); ?>
    <?php echo $view->renderExtraCSS(); ?>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/style-extra.css">
    <noscript>Enable Javascript with your browser.</noscript>
</head>
<body>
    <header class="du-header">

    </header>
    <div class="reg-form">

        <?php include($middle); ?>


</body>
</html>