<script type="text/javascript">
    $(document).ready( function(){

        $("#checkcredentails").validate({
            rules: {
                password:{required:true}
            },
            messages: {
                password:{required:"<?php echo _l('Enter_Password', 'users'); ?>"}
            }
        });
    });
</script>
<div class="wrapper">
    <div class="container con-padding-tb">
        <form name="checkcredentails" id="checkcredentails" method="post" enctype="multipart/form-data">
            <div class="col-sm-12 text-center">
                <div class="col-md-2 grids hidden-sm"></div>
                <div class="col-md-8 grids col-sm-12">
                    <div class="profile-sec-head pink">
                        <div class="col-md-12 col-sm-12">
                            <span class="profile-sec-head profile-head-bg">
                                <?php if ($view->userDetails['user_image'] != "") { ?>
                                    <img src="<?php echo $module_url . '/images/user_images/' . $view->userDetails['user_image']; ?>" height="120px" width="120px" alt="" />
                                <?php } else { ?>
                                    <img src="" height="120px" width="120px" alt="" />
                                <?php } ?>
                            </span>
                        </div>
                    </div>

                    <div class="registration">
                        <?php include($module_path . "/application/global/message.php"); ?>
                        <div class="custom-form">

                            <div class="col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Password', 'users'); ?><span class="require">*</span></label>
                                    <input class="input-style pull-left" type="password" placeholder="<?php echo _l('Placeholder_Password', 'users'); ?>" name="password" id="password"/>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <input type="submit" name="submit" value="<?php echo _l('Button_Submit', 'common'); ?>" class="register-btn" style="margin-top:10px;" />
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-2 grids hidden-sm">&nbsp;</div>
            </div>
        </form>
    </div>
</div>