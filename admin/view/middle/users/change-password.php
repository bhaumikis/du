<script type="text/javascript">
    $(document).ready( function(){

        $("#changepassword").validate({
            rules: {
                opassword:{required:true},
                password:{required:true,regex:true},
                cpassword:{required:true,equalTo: "#password"}
            },
            messages: {
                opassword:{required:"<?php echo _l('Enter_Old_Password', 'change_password'); ?>"},
                password:{required:"<?php echo _l('Enter_New_Password', 'change_password'); ?>"},
                cpassword:{required:"<?php echo _l('Confirm_New_Password', 'change_password'); ?>",equalTo:"<?php echo _l('Password_Confirm_New_Password_Same', 'change_password'); ?>"}
            }
        });
        $.validator.addMethod('regex', function (value) {
            return /\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/.test(value);
        }, '<?php echo _l('Invalid_Password_String', 'change_password'); ?>');


    });
</script>
<div class="wrapper">

    <div class="container con-padding-tb">
        <form name="changepassword" id="changepassword" method="post" enctype="multipart/form-data">
            <div class="col-md-2 grids hidden-sm"></div>
            <div class="col-md-8 grids col-sm-12">
                <div class="profile-sec-head pink">
                    <div class="col-md-12 col-sm-12"> <span class="pic-profile"> </span> </div>
                </div>
                <div class="registration">
                    <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
                    <div class="custom-form">
                        <div class="col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_Old_Password', 'change_password'); ?></label>
                                <input class="input-style pull-left" type="password" placeholder="<?php echo _l('Placeholder_Old_Password', 'change_password'); ?>" name="opassword" id="opassword"/>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_New_Password', 'change_password'); ?></label>
                                <input class="input-style pull-left" type="password" placeholder="<?php echo _l('Placeholder_New_Password', 'change_password'); ?>" name="password" id="password"/>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_Confirm_Password', 'change_password'); ?></label>
                                <input class="input-style pull-left" type="password" placeholder="<?php echo _l('Placeholder_Confirm_Password', 'change_password'); ?>" name="cpassword" id="cpassword"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 pro-btm-fix">
                            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                                <button type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" onclick="window.location.href = '<?php echo $module_url . "/users/manage-my-account"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
                            </div>
                            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                                <input type="submit" name="submit" value="<?php echo _l('Button_Submit', 'common'); ?>" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 grids hidden-sm">&nbsp;</div>
    </div>
</form>
</div>
</div>
