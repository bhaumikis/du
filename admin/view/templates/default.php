<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo _l("Daily Use", "common"); ?></title>

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/bootstrap.min.css" type="text/css" /><!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/font-awesome-4.0.3/css/font-awesome.css" type="text/css" /><!-- Font Awesome -->


    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPLICATION_URL; ?>/css/daterangepicker-bs3.css" /><!-- Date Range Picker -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/style.css" type="text/css" /><!-- Style -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/responsive.css" type="text/css" /><!-- Responsive -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/datepicker3.css" type="text/css" /><!-- date picker -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/flip-scroll.css" type="text/css" /><!-- Rotating Table -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/msdropdown/dd.css" type="text/css" /><!-- flag country -->
    <link rel="stylesheet" href="<?php echo APPLICATION_URL; ?>/css/msdropdown/flags.css" type="text/css" /><!-- flag country -->
    <?php echo $view->renderExtraCSS(); ?>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/admin-style-extra.css">
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/media/jquery.dataTables.css"><!-- Data table -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/media/dataTables.colReorder.css"><!-- Data table -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/summernote/summernote.css"><!-- Editor -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/summernote/summernote-bs2.css"><!-- Editor -->
    <link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/css/summernote/summernote-bs3.css"><!-- Editor -->

    <!-- Script -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-1.10.2.js"></script><!-- Jquery -->

    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/bootstrap.min.js"></script><!-- Bootstrap -->
    <script type="text/javascript"  src="<?php echo APPLICATION_URL; ?>/js/script.js"></script><!-- Script -->
    <script src="<?php echo APPLICATION_URL; ?>/js/skycons.js"></script> <!-- Skycons -->
    <script src="<?php echo APPLICATION_URL; ?>/js/enscroll-0.5.2.min.js"></script> <!-- Custom Scroll bar -->
    <script src="<?php echo APPLICATION_URL; ?>/js/moment.js"></script> <!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/daterangepicker.js"></script><!-- Date Range Picker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/ticker.js"></script><!-- Ticker -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery.validate.js"></script><!-- Validation -->
    <script src="<?php echo APPLICATION_URL; ?>/js/jquery-validate.bootstrap-tooltip.js"></script><!-- Validation -->
    <?php echo $view->renderExtraJS(); ?>

    <script src="<?php echo APPLICATION_URL; ?>/js/common.js" type="text/javascript"></script><!-- common js -->
    <script src="<?php echo APPLICATION_URL; ?>/js/msdropdown/jquery.dd.min.js"></script><!-- flag country -->
    <script src="<?php echo APPLICATION_URL; ?>/js/media/jquery.dataTables.js"></script><!-- Data table -->
    <script src="<?php echo APPLICATION_URL; ?>/js/media/dataTables.colReorder.js"></script><!-- Data table -->
    <script src="<?php echo APPLICATION_URL; ?>/js/media/jquery.dataTables.columnFilter.js"></script><!-- Data table -->
    <script src="<?php echo APPLICATION_URL; ?>/js/summernote/summernote.min.js"></script><!-- Editor -->
    <noscript>Enable Javascript with your browser.</noscript>
</head>
<body>

    <!-- Start: Header -->
    <header class="">
        <?php include($module_path . "/view/header/header.php"); ?>
    </header>
    <!-- End: Header -->

    <!-- Left Menu -->
    <div class="menu">
        <?php include($module_path . "/view/left/left.php"); ?>
    </div>
    <!-- Left Menu -->

    <?php include($middle); ?>

    <!-- RAIn ANIMATED ICON-->
    <script>
        var icons = new Skycons();
        icons.set("rain", Skycons.RAIN);
        icons.play();
    </script>


    <div id="dialog-form" style="display: none;" title="Edit">
        <iframe src=""  width="100%" height="100%" id="contentedit" class="logoiframe" /></iframe>
    </div>
</body>
</html>
