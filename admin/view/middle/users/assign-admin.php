<script type="text/javascript">
    $(document).ready( function() {
        $("#assignadmin").validate({
            rules: {
                admin_id:{required:true}
            },
            messages: {
                admin_id:{required:"<?php echo _l('Enter_Old_Password', 'change_password'); ?>"}
            }
        });
    });
</script>
<div class="wrapper">
    <div class="container con-padding-tb">
        <form name="assignadmin" id="assignadmin" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $view->userid;?>" />
            <input type="hidden" name="assigned_admin_id" id="assigned_admin_id" value="<?php echo $view->assignedadmin['admin_id'];?>" />
            <div class="col-md-8 grids col-sm-12">
                <div class="registration">
                    <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
                    <div class="custom-form">
                        <div class="col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_Assign_Admin', 'administrators'); ?></label>
                                <select class="form-control" name="admin_id" id="admin_id"/>
                                <option value=""><?php echo _l('Label_Select_Admin', 'administrators'); ?></option>
                                <?php
                                if (isset($view->adminlist)) {
                                    foreach ($view->adminlist as $userid => $name) {
                                        ?>
                                        <option value="<?php echo $userid; ?>"
                                                <?php echo ($view->assignedadmin['admin_id'] == $userid) ? 'selected="selected"' : ""; ?>><?php echo $name; ?></option>
                                    <?php }
                                } ?>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="margin-top-15 col-xs-6 col-md-6 col-sm-6 pull-left padding-right-0 padding-left-0">
                                <input type="submit" name="submit" value="<?php echo _l('Button_Assign', 'common'); ?>" class="pro-btns col-sm-12 col-xs-12 padding-left-0" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
