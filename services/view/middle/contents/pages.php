
<section class="widget-large w-manage-customers">
    <?php if (count($view->pages)) { ?>
        <div class="widget-header">
            <h2 class="widget-title text-orange-light">Pages</h2>
        </div>
        <div>&nbsp;</div>
        <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>

        <div class="widget-content">
            <?php for ($i = 0; $i < count($view->pages); $i++) { ?>
                <br><a href="<?php echo $module_url . "/contents/page/content_id/" . $view->pages[$i]['content_id'] . "/language/" . $_REQUEST['language']; ?>"><?php echo $view->pages[$i]['title'] ?></a></br>
            <?php } ?>
        </div> <?php } else { ?>
        <div style="text-align: center;">No Record Found.</div>
    <?php } ?>
</section>

